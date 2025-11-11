=== Meeting List Lite ===

Contributors: pjaudiomv
Plugin URI: https://wordpress.org/plugins/meeting-list-lite/
Tags: meeting list, recovery, addiction
Requires PHP: 8.0
Tested up to: 6.8
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a WordPress plugin for displaying 12-Step Meeting Information.

== Description ==

Meeting List Lite is a streamlined solution for displaying 12-step meeting information on your WordPress site when your meeting data is maintained elsewhere. Unlike the full 12 Step Meeting List plugin, this "lite" version doesn't include a database or editing capabilities, it simply displays meetings from your existing data source.

**Perfect for service bodies who:**
* Maintain their meeting data in a Google Sheet, JSON feed, BMLT or another system
* Don't need to edit meeting information within WordPress
* Want the familiar TSML UI interface without the overhead of data management
* Already have geocoded meeting data (latitude/longitude for in-person meetings)

**Key Features:**
* Displays meetings using the same TSML UI interface as 12 Step Meeting List
* Supports JSON feeds and Google Sheets that follow the [TSML spec](https://github.com/code4recovery/spec)
* No database required—your data source is the single source of truth
* Lightweight and easy to set up
* Fully compatible with the TSML data format

**Data Source Requirements:**
Your data source must follow the [TSML specification](https://github.com/code4recovery/spec). For in-person meetings, latitude and longitude coordinates must already be included in your data, this plugin does not perform geocoding.

To display your meetings, simply add the `[tsml_ui]` shortcode to any page or post, and configure your data source URL in the plugin settings.

**Important:** Your WordPress timezone should be set to a valid IANA timezone identifier in Settings → General. See the [PHP timezone list](https://www.php.net/manual/en/timezones.php) for valid values. Generic UTC offsets (like "UTC+5") are not sufficient. You can also override the timezone using the `timezone` shortcode attribute.

== Installation ==

1. Upload the `meeting-list-lite` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. **Important:** Ensure your WordPress timezone is set to a valid IANA timezone (e.g., "America/New_York") in Settings → General for accurate meeting times. You can also configure through shortcode attribute. See https://www.php.net/manual/en/timezones.php for a complete list.
4. Go to Settings → Meeting List Lite and configure your data source URL
5. Add the `[tsml_ui]` shortcode to any WordPress page or post where you want meetings displayed

**Shortcode Usage:**
Basic: `[tsml_ui]`
With custom data source: `[tsml_ui data_source="https://your-url.com/meetings.json"]`
With custom timezone: `[tsml_ui timezone="America/New_York"]`
Combined: `[tsml_ui data_source="https://your-url.com/meetings.json" timezone="America/New_York"]`

== Screenshots ==

1. Plugin settings
1. Meeting map

== Frequently Asked Questions ==

= What's the difference between this and 12 Step Meeting List? =

12 Step Meeting List is a full-featured plugin that stores meeting data in your WordPress database and includes editing
capabilities, geocoding, and data management tools. Meeting List Lite is designed for service bodies who maintain their meeting
data elsewhere and just need to display it on their WordPress site.

= What data formats are supported? =

The plugin supports JSON feeds and Google Sheets that conform to the [TSML specification](https://github.com/code4recovery/spec).

= Does this plugin geocode addresses? =

No. Your data source must already include latitude and longitude coordinates for in-person meetings. If you need geocoding, consider using the full 12 Step Meeting List plugin instead.

= Can I edit meeting data in WordPress? =

No. This is a display-only plugin. Your data source (JSON feed, Google Sheet, etc.) is the single source of truth. To edit meetings, update your external data source.

= Where do I configure my data source? =

Go to Settings → Meeting List Lite in your WordPress dashboard, or use the `data_source` attribute in your shortcode.

= Why does my WordPress timezone need to be set to a valid IANA timezone? =

The plugin requires a proper IANA timezone identifier (like "America/New_York" or "Europe/London") to correctly display meeting times. Generic UTC offsets (like "UTC+5") are not sufficient. You can set this in Settings → General, override it with the `timezone` shortcode attribute. Find a complete list of valid timezones at https://www.php.net/manual/en/timezones.php

== External services ==

This plugin relies on external services to function properly:

**TSML UI React Component**
- **Service**: Code4Recovery TSML UI (tsml-ui.code4recovery.org)
- **Purpose**: Provides the JavaScript React component that renders the meeting list interface
- **Data sent**: No user data is transmitted to this service. The plugin only loads the JavaScript library.
- **When**: The script is loaded whenever a page contains the [tsml_ui] shortcode
- **Terms of use**: https://github.com/code4recovery/tsml-ui/blob/main/LICENSE
- **Privacy policy**: https://code4recovery.org/privacy/

**Your Data Source**
- You must configure your own data source URL (JSON feed or Google Sheet) in the plugin settings
- The plugin fetches meeting data from this URL to display on your site
- No data is sent to your data source; the plugin only reads from it

== Changelog ==

= 1.2.3 =
* Added base_path as a shortcode attribute

= 1.2.2 =
* Added configurable TSML CDN URL setting in Advanced Settings

= 1.2.1 =
* Set default marker when using a BMLT server.

= 1.2.0 =

* Added CSS template selection dropdown with "Full Width" and "Full Width (Force)" options

= 1.1.1 =

* Improved default meeting type labels when using BMLT data sources

= 1.1.0 =

* Added configurable base path setting for pretty URLs (e.g., `/meetings/slug-name`)
* Added automatic rewrite rule management with version tracking
* Added plugin activation and deactivation hooks for proper permalink handling
* Improved settings page with new "Base Path for Pretty URLs" option
* Enhanced URL routing to support client-side navigation with React Router

= 1.0.8 =

* Add google key setting.

= 1.0.7 =

* Remove base css and just use as default custom css.

= 1.0.6 =

* Remove unneeded google key setting.

= 1.0.5 =

* Change css customization example.

= 1.0.4 =

* Revert setting of default timezone.

= 1.0.3 =

* Updated note about setting timezone.

= 1.0.2 =

* Add plugin screenshots

= 1.0.1 =

* Support for JSON and Google Sheet data sources
* TSML UI integration for meeting display
* Shortcode support with configurable attributes

= 1.0.0 =

* Initial Release
