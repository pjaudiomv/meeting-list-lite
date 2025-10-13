---
sidebar_position: 5
---

# Frequently Asked Questions

Common questions and answers about Meeting List Lite.

## General Questions

### What's the difference between this and 12 Step Meeting List?

12 Step Meeting List is a full-featured plugin that stores meeting data in your WordPress database and includes editing capabilities, geocoding, and data management tools. Meeting List Lite is designed for service bodies who maintain their meeting data elsewhere and just need to display it on their WordPress site.

### What data formats are supported?

The plugin supports JSON feeds and Google Sheets that conform to the [TSML specification](https://github.com/code4recovery/spec).

### Does this plugin geocode addresses?

No. Your data source must already include latitude and longitude coordinates for in-person meetings. If you need geocoding, consider using the full 12 Step Meeting List plugin instead.

### Can I edit meeting data in WordPress?

No. This is a display-only plugin. Your data source (JSON feed, Google Sheet, etc.) is the single source of truth. To edit meetings, update your external data source.

### Where do I configure my data source?

Go to **Settings** → **Meeting List Lite** in your WordPress dashboard, or use the `data_src` attribute in your shortcode.

## Technical Questions

### Why does my WordPress timezone need to be set to a valid IANA timezone?

The plugin requires a proper IANA timezone identifier (like "America/New_York" or "Europe/London") to correctly display meeting times. Generic UTC offsets (like "UTC+5") are not sufficient. You can set this in Settings → General, or override it with the `timezone` shortcode attribute. Find a complete list of valid timezones at https://www.php.net/manual/en/timezones.php

### What external services does this plugin use?

The plugin relies on two external services:

1. **TSML UI React Component** from Code4Recovery (tsml-ui.code4recovery.org) - provides the JavaScript component that renders the meeting list interface
2. **Your Data Source** - the URL you configure to fetch meeting data from

### How do I troubleshoot CORS errors?

If you're getting CORS (Cross-Origin Resource Sharing) errors:

1. Ensure your data source server includes proper CORS headers
2. Add these headers to your server response:
   ```
   Access-Control-Allow-Origin: *
   Access-Control-Allow-Methods: GET
   ```
3. Consider using a CORS proxy service for testing
4. Contact your hosting provider if you can't modify server headers

### Can I use this plugin with a membership/restricted site?

Yes, but ensure your data source URL is publicly accessible or configure appropriate authentication headers.

## Data Source Questions

### What's the minimum data required for each meeting?

Each meeting must include:
- `name`: Meeting name
- `day`: Day of week (0=Sunday, 1=Monday, etc.)
- `time`: Meeting time in HH:MM format
- `types`: Array of meeting type codes

### How do I format meeting times?

Use 24-hour format (HH:MM):
- `"07:00"` for 7:00 AM
- `"19:30"` for 7:30 PM
- `"12:00"` for noon

### What meeting type codes should I use?

Common TSML meeting type codes:
- `O` - Open
- `C` - Closed
- `M` - Men only
- `W` - Women only
- `TC` - Temporarily Closed
- `ONL` - Online
- `HY` - Hybrid (both in-person and online)

### How do I handle online meetings?

For online meetings, include:
- `conference_url`: Meeting URL (Zoom, etc.)
- `conference_phone`: Dial-in number (optional)
- `types`: Include "ONL" in the types array

Example:
```json
{
  "name": "Online Meeting",
  "day": 1,
  "time": "19:00",
  "types": ["O", "ONL"],
  "conference_url": "https://zoom.us/j/123456789"
}
```

## Styling and Customization

### How do I change the appearance of the meeting list?

Add custom CSS through:
1. Plugin settings (Meeting List Lite → Custom CSS)
2. Your theme's additional CSS (Appearance → Customize → Additional CSS)
3. Your theme's style.css file

### Can I translate the interface?

The TSML UI component supports multiple languages. You can customize strings using the TSML UI Configuration in the plugin settings.

### How do I hide certain meeting types?

Use the TSML UI Configuration to customize which meeting types are displayed:

```json
{
  "strings": {
    "en": {
      "type_descriptions": {
        "C": null
      }
    }
  }
}
```

## Performance and Caching

### How often does the plugin fetch new data?

The plugin fetches data from your source every time a user loads a page with the shortcode. The TSML UI component may cache data in the browser for performance.

### Can I cache the meeting data?

While the plugin doesn't include server-side caching, you can:
1. Enable caching on your data source server
2. Use a CDN to serve your JSON data
3. Use a WordPress caching plugin that caches JavaScript requests

### My site is slow when loading meetings. What can I do?

1. Optimize your data source for speed
2. Minimize the size of your JSON data
3. Use a fast, reliable hosting provider for your data source
4. Enable GZIP compression on your data source server

## Troubleshooting

### The shortcode shows as text instead of the meeting list

This usually means:
1. The plugin is not activated
2. There's a PHP error preventing the shortcode from processing
3. Your theme doesn't support shortcodes in that location

Check your WordPress error logs and ensure the plugin is active.

### Meetings aren't showing up

Common causes:
1. Invalid JSON in your data source
2. CORS errors (check browser console)
3. Network connectivity issues
4. Incorrect data source URL

### Maps aren't working

This is usually because:
1. Missing latitude/longitude coordinates in your data
2. JavaScript errors (check browser console)

### Getting JavaScript errors

1. Check browser console for specific error messages
2. Ensure your data source returns valid JSON
3. Try disabling other plugins to check for conflicts
4. Switch to a default theme temporarily to test

### Pretty URLs not working

If pretty URLs like `/meetings/some-meeting` show 404 errors:

1. **Check WordPress permalinks**:
   - Go to **Settings** → **Permalinks**
   - Ensure "Pretty permalinks" is enabled (not "Plain")
   - Click **Save Changes** to flush rewrite rules

2. **Verify base path setting**:
   - Go to **Settings** → **Meeting List Lite**
   - Ensure the Base Path matches your page slug exactly
   - If your meetings page is `/meetings/`, enter `meetings`

3. **Flush permalinks manually**:
   - After changing the Base Path setting, always flush permalinks
   - **Settings** → **Permalinks** → **Save Changes**

4. **Check .htaccess permissions**:
   - Ensure WordPress can write to your `.htaccess` file
   - File permissions should be 644 or 664
   - File should be owned by your web server user

5. **Server configuration**:
   - Some hosting providers disable URL rewriting
   - Contact your host if permalinks work elsewhere but not for pretty URLs
   - Consider using hash routing instead if server limitations exist

**Fallback to hash routing**:
If pretty URLs won't work in your environment, leave the Base Path setting empty to use standard hash routing (`/meetings/#/some-meeting`).

## Support

### Where can I get help?

1. Check this documentation first
2. Search the [WordPress plugin support forum](https://wordpress.org/support/plugin/meeting-list-lite/)
3. Create a new support topic if your question isn't answered
4. Report bugs on [GitHub](https://github.com/pjaudiomv/meeting-list-lite/issues)

### How do I report a bug?

1. Check the [GitHub issues](https://github.com/pjaudiomv/meeting-list-lite/issues) to see if it's already reported
2. If not, create a new issue with:
   - WordPress version
   - PHP version
   - Plugin version
   - Steps to reproduce the problem
   - Any error messages

### Can I contribute to the plugin?

Yes! The plugin is open source. You can:
1. Report bugs
2. Suggest improvements
3. Submit pull requests
4. Help with documentation
5. Test beta versions

Visit the [GitHub repository](https://github.com/pjaudiomv/meeting-list-lite) to get involved.
