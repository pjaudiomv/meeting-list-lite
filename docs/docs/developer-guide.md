---
sidebar_position: 8
---

# Developer Guide

Information for developers who want to contribute to Meeting List Lite or understand its internals.

## API Documentation

The complete PHP API documentation is available at [API Reference](../api) and provides detailed information about:

- **Classes**: All plugin classes with their methods and properties
- **Functions**: Global functions and utilities
- **Hooks**: WordPress actions and filters used by the plugin
- **Constants**: Plugin constants and configuration values

### Key Classes

- **`MEETINGLISTLITE`**: Main plugin class that handles initialization and core functionality
- **Configuration Management**: Settings and options handling
- **Shortcode Processing**: The `[tsml_ui]` shortcode implementation
- **Asset Management**: JavaScript and CSS loading

## Development Setup

### Prerequisites

- **PHP 8.0+**: Required for development
- **Node.js 20+**: For building documentation
- **Docker**: For local development environment
- **Composer**: For PHP dependencies

### Local Development

1. **Clone the repository:**
```bash
git clone https://github.com/pjaudiomv/meeting-list-lite.git
cd meeting-list-lite
```

2. **Install dependencies:**
```bash
composer install
cd docs && npm install && cd ..
```

3. **Start development environment:**
```bash
make dev
```

This will start a local WordPress instance with the plugin activated.

### Documentation Development

#### Build All Documentation
```bash
make docs
```

This builds both PHP API docs and Docusaurus documentation.

#### Build Separately
```bash
# Build only PHP API documentation
make api-docs

# Build only Docusaurus documentation
make docusaurus
```

#### Development Server
```bash
# Start Docusaurus development server with live reload
make docusaurus-dev
```

The documentation will be available at `http://localhost:3000`

#### Clean Documentation
```bash
# Clean all documentation builds
make clean-docs
```

## Plugin Architecture

### File Structure

```
meeting-list-lite/
├── meeting-list-lite.php    # Main plugin file
├── index.php               # Security index file
├── uninstall.php          # Uninstall cleanup
├── composer.json          # PHP dependencies
├── docs/                  # Docusaurus documentation
├── api-docs/             # Generated PHP API docs
└── assets/               # Plugin assets
```

### Main Plugin Class

The `MEETINGLISTLITE` class follows a singleton pattern:

```php
class MEETINGLISTLITE {
    private static ?self $instance = null;
    
    public function __construct() {
        add_action('init', [$this, 'plugin_setup']);
    }
    
    public function plugin_setup(): void {
        // Initialize based on context (admin vs frontend)
    }
}
```

### Shortcode Implementation

The `[tsml_ui]` shortcode is handled by the `setup_shortcode` method:

```php
public static function setup_shortcode(string|array $attrs = []): string {
    $attrs = shortcode_atts([
        'data_src'   => '',
        'timezone'   => '',
    ], (array) $attrs, 'tsml_ui');
    
    // Process attributes and return HTML
}
```

### Settings and Configuration

Plugin settings are managed through WordPress options:
- `meetinglistlite_data_src`: Data source URL
- `meetinglistlite_google_key`: Google Maps API key
- `meetinglistlite_tsml_config`: TSML UI configuration JSON
- `meetinglistlite_custom_css`: Custom CSS styles

## Contributing

### Code Standards

The project follows WordPress coding standards with some modifications:

- **PHP**: WordPress PHP Coding Standards
- **JavaScript**: Standard WordPress JavaScript guidelines
- **CSS**: WordPress CSS Coding Standards

### Testing

#### PHP Code Style
```bash
# Check code style
make lint

# Fix code style issues
make fmt
```

#### Manual Testing

1. Activate the plugin in your development environment
2. Configure a test data source
3. Add the shortcode to a test page
4. Verify functionality works as expected

### Hooks and Filters

The plugin provides several hooks for customization:

#### Actions
- `meetinglistlite_before_shortcode`: Before shortcode processing
- `meetinglistlite_after_shortcode`: After shortcode processing

#### Filters
- `meetinglistlite_data_src`: Filter the data source URL
- `meetinglistlite_shortcode_attrs`: Filter shortcode attributes
- `meetinglistlite_tsml_config`: Filter TSML UI configuration

Example usage:
```php
// Override data source based on user role
add_filter('meetinglistlite_data_src', function($url) {
    if (current_user_can('manage_options')) {
        return 'https://admin.example.com/meetings.json';
    }
    return $url;
});
```

## Data Flow

1. **Shortcode Processing**: WordPress processes `[tsml_ui]` shortcode
2. **Attribute Parsing**: Plugin parses shortcode attributes
3. **Configuration**: Merges attributes with global settings
4. **HTML Generation**: Creates container with data attributes
5. **Asset Loading**: Enqueues TSML UI JavaScript
6. **Frontend Rendering**: TSML UI fetches and displays meetings

## External Dependencies

### TSML UI Component
- **Source**: [Code4Recovery TSML UI](https://github.com/code4recovery/tsml-ui)
- **Loading**: Via CDN (`https://tsml-ui.code4recovery.org/app.js`)
- **Version**: Automatically updated by Code4Recovery

### Development Dependencies
- **PHPDoc**: For API documentation generation
- **PHP_CodeSniffer**: For code style checking
- **Docusaurus**: For user documentation

## Debugging

### Enable Debug Mode

Add to your WordPress `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### JavaScript Debugging

Add `?debug=1` to any page with the shortcode to enable console logging:
```
https://yoursite.com/meetings/?debug=1
```

### Common Issues

1. **Meetings not loading**: Check data source URL and CORS headers
2. **Maps not working**: Verify Google Maps API key and coordinates
3. **Styling issues**: Check for theme CSS conflicts

## Release Process

1. Update version in `meeting-list-lite.php`
2. Update `readme.txt` changelog
3. Update `CHANGELOG.md`
4. Create GitHub release
5. Submit to WordPress Plugin Directory

## Support

For development questions:
- Check the [API documentation](../api)
- Review existing [GitHub issues](https://github.com/pjaudiomv/meeting-list-lite/issues)
- Create a new issue with detailed information

## Security

### Input Sanitization

All user inputs are sanitized:
```php
$data_src = esc_url_raw($attrs['data_src']);
$google_key = sanitize_text_field($attrs['google_key']);
$timezone = sanitize_text_field($attrs['timezone']);
```

### Output Escaping

All outputs are properly escaped:
```php
$content = '<div id="tsml-ui" data-src="' . esc_url($data_src) . '">';
```

### Security Best Practices

- Never trust user input
- Validate and sanitize all data
- Escape output appropriately
- Use nonces for form submissions
- Follow WordPress security guidelines