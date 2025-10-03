COMMIT := $(shell git rev-parse --short=8 HEAD)
ZIP_FILENAME := $(or $(ZIP_FILENAME), $(shell echo "$${PWD\#\#*/}.zip"))
BUILD_DIR := $(or $(BUILD_DIR),"build")
VENDOR_AUTOLOAD := vendor/autoload.php

help:  ## Print the help documentation
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: build
build:  ## Build
	@echo "Building with commit: $(COMMIT)"
	@echo "ZIP filename: $(ZIP_FILENAME)"
	@echo "Build directory: $(BUILD_DIR)"
	git archive --format=zip --output=${ZIP_FILENAME} $(COMMIT)
	mkdir -p ${BUILD_DIR} && mv ${ZIP_FILENAME} ${BUILD_DIR}/

.PHONY: clean
clean:  ## clean
	rm -rf build

.PHONY: clean-docs
clean-docs:  ## Clean documentation builds
	rm -rf api-docs docs/build docs/.docusaurus

$(VENDOR_AUTOLOAD):
	composer install --prefer-dist --no-progress

.PHONY: composer
composer: $(VENDOR_AUTOLOAD) ## Runs composer install

.PHONY: lint
lint: composer ## PHP Lint
	vendor/squizlabs/php_codesniffer/bin/phpcs

.PHONY: fmt
fmt: composer ## PHP Fmt
	vendor/squizlabs/php_codesniffer/bin/phpcbf

# API docs target - depends on PHP source files
api-docs/index.html: meeting-list-lite.php uninstall.php
	docker run --rm -v $(shell pwd):/data phpdoc/phpdoc:3 --ignore=vendor/ -d . -t api-docs/

# Docusaurus build target - depends on docs source files
docs/build/index.html: docs/package.json docs/docusaurus.config.ts docs/sidebars.ts $(shell find docs/docs docs/src -name '*.md' -o -name '*.tsx' -o -name '*.ts' 2>/dev/null)
	cd docs && npm ci && npm run build

# Combined docs target - depends on both API and Docusaurus builds
docs/build/api/index.html: api-docs/index.html docs/build/index.html
	mkdir -p docs/build/api
	cp -r api-docs/* docs/build/api/

.PHONY: api-docs
api-docs: api-docs/index.html  ## Generate PHP API Documentation

.PHONY: docusaurus
docusaurus: docs/build/index.html  ## Build Docusaurus documentation

.PHONY: docusaurus-dev
docusaurus-dev:  ## Start Docusaurus development server
	cd docs && npm ci && npm start

.PHONY: docs
docs: docs/build/api/index.html  ## Generate both API docs and Docusaurus documentation
	@echo "Both API documentation and Docusaurus documentation have been built"
	@echo "API docs: ./api-docs/"
	@echo "Docusaurus: ./docs/build/"
	@echo "Combined docs with API: ./docs/build/ (includes /api/ route)"

.PHONY: preview
preview: docs/build/api/index.html  ## Build and serve documentation locally with Python
	@echo "Starting local server at http://localhost:8100"
	@echo "API docs will be available at http://localhost:8100/api/"
	cd docs/build && python3 -m http.server 8100

.PHONY: preview-php
preview-php: docs/build/api/index.html  ## Build and serve documentation locally with PHP
	@echo "Starting local server at http://localhost:8100"
	@echo "API docs will be available at http://localhost:8100/api/"
	cd docs/build && php -S localhost:8100

.PHONY: dev
dev: ## Start dev compose
	docker compose up

.PHONY: mysql
mysql:  ## Runs mysql cli in mysql container
	docker exec -it meeting-list-lite-db-1 mariadb -u root -psomewordpress wordpress

.PHONY: bash
bash:  ## Runs bash shell in wordpress container
	docker exec -it -w /var/www/html meeting-list-lite-wordpress-1 bash
