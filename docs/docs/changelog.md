---
sidebar_position: 7
---

# Changelog

All notable changes to Meeting List Lite are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.6] - 2024-10-04
### Changed
- Remove unneeded google key setting.

## [1.0.4] - 2024-10-02

### Changed
- Reverted setting of default timezone to avoid conflicts with WordPress timezone settings

### Notes
- Users should ensure their WordPress timezone is set to a valid IANA timezone identifier in Settings → General
- Timezone can be overridden per shortcode using the `timezone` attribute

## [1.0.3] - 2024-09-30

### Changed
- Updated documentation and notes about timezone requirements
- Improved clarity around IANA timezone identifier requirements

### Documentation
- Enhanced README with clearer timezone setup instructions
- Added more detailed timezone troubleshooting information

## [1.0.2] - 2024-09-29

### Added
- Plugin screenshots for WordPress repository
- Visual documentation for plugin settings and meeting display

### Improved
- WordPress Plugin Directory assets
- Better visual representation of plugin capabilities

## [1.0.1] - 2024-09-28

### Added
- Support for JSON and Google Sheets data sources
- TSML UI integration for consistent meeting display interface
- Configurable shortcode with multiple attributes
- Google Maps integration for meeting location display

### Features
- `[tsml_ui]` shortcode with customizable attributes:
  - `data_src`: Override data source URL
  - `google_key`: Override Google Maps API key  
  - `timezone`: Override timezone setting
- Plugin settings page for global configuration
- Custom CSS support for styling customization
- TSML UI configuration for advanced interface customization

### Security
- Input sanitization for all plugin options
- Secure handling of external data sources
- Proper escaping of output data

## [1.0.0] - 2024-09-15

### Added
- Initial release of Meeting List Lite
- Basic plugin structure and activation
- WordPress Plugin Directory submission
- Core functionality for displaying external meeting data

### Features
- Lightweight plugin architecture
- External data source integration
- TSML specification compliance
- Basic shortcode implementation

---

## Upgrade Notes

### From 1.0.3 to 1.0.4
No action required. The timezone default setting change only affects new installations.

### From 1.0.2 to 1.0.3
No breaking changes. Documentation updates only.

### From 1.0.1 to 1.0.2
No functional changes. Visual assets added for WordPress repository.

### From 1.0.0 to 1.0.1
Major feature addition. If upgrading from 1.0.0:
1. Check that your WordPress timezone is properly configured
2. Verify your data source follows the TSML specification
3. Update any custom implementations to use the new shortcode syntax

---

## Data Source Compatibility

All versions support data sources that follow the [TSML specification](https://github.com/code4recovery/spec):

### Required Fields
- `name`: Meeting name
- `day`: Day of week (0-6, where 0=Sunday)
- `time`: Meeting time in 24-hour format (HH:MM)
- `types`: Array of meeting type codes

### Optional Fields
- `location`: Meeting location name
- `address`: Street address
- `latitude` & `longitude`: GPS coordinates for mapping
- `region`: Geographic region
- `notes`: Additional information
- `conference_url`: Online meeting URL
- `conference_phone`: Phone number for dial-in

---

## External Dependencies

### TSML UI Component
- **Version Compatibility**: Automatically uses latest compatible version
- **Source**: [Code4Recovery TSML UI](https://github.com/code4recovery/tsml-ui)
- **License**: MIT
- **Updates**: Automatic via CDN

### WordPress Compatibility
- **Minimum WordPress Version**: 5.3
- **Tested Up To**: 6.8
- **PHP Requirement**: 8.0+

---

## Known Issues

### Version 1.0.4
- None currently known

### Previous Versions
- ~~1.0.3: Timezone documentation could be clearer~~ (Fixed in 1.0.4)
- ~~1.0.2: Missing visual assets in repository~~ (Fixed in 1.0.3)
- ~~1.0.1: Default timezone handling inconsistent~~ (Fixed in 1.0.4)

---

## Roadmap

### Planned Features
- Enhanced caching options
- Additional data source formats
- Improved mobile responsiveness
- Advanced filtering options
- Multi-language support improvements

### Under Consideration
- WordPress widget for meeting display
- Integration with popular page builders
- Advanced customization options
- Meeting data validation tools

---

## Support

For support with any version:
- [WordPress Plugin Forum](https://wordpress.org/support/plugin/meeting-list-lite/)
- [GitHub Issues](https://github.com/pjaudiomv/meeting-list-lite/issues)
- [Documentation](https://pjaudiomv.github.io/meeting-list-lite/)

---

## Contributing

We welcome contributions! See our [Contributing Guide](https://github.com/pjaudiomv/meeting-list-lite/blob/main/CONTRIBUTING.md) for details on:
- Reporting bugs
- Suggesting features
- Submitting pull requests
- Testing beta versions

---

*This changelog is maintained by pjaudiomv and follows the [Keep a Changelog](https://keepachangelog.com/) format.*
