=== Meeting List Lite ===

Contributors: pjaudiomv
Plugin URI: https://wordpress.org/plugins/meeting-list-lite/
Tags: meeting list, recovery, addiction
Requires PHP: 8.0
Tested up to: 6.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a WordPress plugin for displaying 12-Step Meeting Information.

== Description ==

This is a WordPress plugin for displaying 12-Step Meeting Information.
To use this, specify [tsml_ui] in your page short code.

== Installation ==

1. Upload `the meeting-list-lite` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add [tsml_ui] shortcode to your WordPress page/post.
4. You can change the plugin settings either in the WordPress dashboard under Settings->Meeting List Lite or using shortcode attributes.

== External services ==

This plugin relies on external services to function properly:

**TSML UI React Component**
- **Service**: Code4Recovery TSML UI (tsml-ui.code4recovery.org)
- **Purpose**: Provides the JavaScript React component that renders the meeting list interface
- **Data sent**: No user data is transmitted to this service. The plugin only loads the JavaScript library.
- **When**: The script is loaded whenever a page contains the [tsml_ui] shortcode
- **Terms of use**: https://github.com/code4recovery/tsml-ui/blob/main/LICENSE
- **Privacy policy**: https://code4recovery.org/privacy/

**Note**: You can configure your own data source URL in the plugin settings to avoid using the default external service.

== Changelog ==

= 1.0.0 =

* Initial Release
