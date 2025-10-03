---
sidebar_position: 3
---

# Configuration

Configure Meeting List Lite to display your meeting data from external sources.

## Plugin Settings

Access the plugin settings by navigating to **Settings** → **Meeting List Lite** in your WordPress dashboard.

### Data Source URL

This is the most important setting. Enter the URL to your meeting data source.

**Supported formats:**
- JSON feeds following the [TSML specification](https://github.com/code4recovery/spec)
- Google Sheets (published as JSON)
- Any URL returning TSML-compliant JSON

**Example URLs:**
```
https://your-domain.com/meetings.json
https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit#gid=0
https://api.your-service.com/meetings
```

### Google Maps API Key (Optional)

If you want to display maps for in-person meetings, you'll need a Google Maps API key.

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the following APIs:
   - Maps JavaScript API
   - Geocoding API (if needed)
4. Create credentials (API key)
5. Restrict the API key to your domain for security
6. Enter the API key in the plugin settings

:::tip
Maps will work without an API key for development, but you'll see a watermark and usage limitations.
:::

### Timezone Override

While it's recommended to set your WordPress timezone globally, you can override it here if needed.

Use IANA timezone identifiers like:
- `America/New_York`
- `Europe/London`
- `Asia/Tokyo`
- `Australia/Sydney`

## Advanced Configuration

### TSML UI Configuration

The plugin allows you to customize the TSML UI component with a JSON configuration. This is for advanced users who want to modify the interface behavior.

**Example configuration:**
```json
{
  "strings": {
    "en": {
      "type_descriptions": {
        "O": null,
        "C": null
      }
    }
  }
}
```

### Custom CSS

Add custom CSS to style the meeting list interface. This CSS will be applied only to pages containing the `[tsml_ui]` shortcode.

**Example CSS:**
```css
.meetinglistlite-fullwidth {
  margin: 20px 0;
}

#tsml-ui {
  font-family: Arial, sans-serif;
}

.tsml-meeting {
  border: 1px solid #ddd;
  margin-bottom: 10px;
  padding: 10px;
}
```

## Data Source Requirements

### TSML Specification

Your data source must follow the [TSML specification](https://github.com/code4recovery/spec). Key requirements:

1. **JSON Format**: Data must be valid JSON
2. **Meeting Structure**: Each meeting must include required fields
3. **Geocoding**: In-person meetings need `latitude` and `longitude`
4. **Types**: Use standard meeting type codes

### Required Fields

Each meeting in your JSON should include:
- `name`: Meeting name
- `day`: Day of week (0=Sunday, 1=Monday, etc.)
- `time`: Meeting time in HH:MM format
- `types`: Array of meeting type codes

### Optional Fields

- `latitude` & `longitude`: For in-person meetings
- `address`: Meeting location address
- `location`: Meeting location name
- `region`: Geographic region
- `notes`: Additional information
- `conference_url`: For online meetings
- `conference_phone`: For phone meetings

### Example Data Structure

```json
[
  {
    "name": "Morning Meditation",
    "day": 1,
    "time": "07:00",
    "location": "Community Center",
    "address": "123 Main St, Anytown, ST 12345",
    "latitude": 40.7128,
    "longitude": -74.0060,
    "region": "Downtown",
    "types": ["M", "TC"],
    "notes": "Enter through side door"
  }
]
```

## Google Sheets Setup

To use a Google Sheet as your data source:

1. Create a Google Sheet with the appropriate columns
2. Go to **File** → **Share** → **Publish to web**
3. Choose **Comma-separated values (.csv)** format
4. Copy the published URL
5. Use a service like [CSV to JSON converter](https://csvjson.com/csv2json) or set up automatic conversion

## Testing Your Configuration

1. Save your settings in the WordPress admin
2. Create a test page with the `[tsml_ui]` shortcode
3. Visit the page to see if meetings load
4. Check browser developer tools for any JavaScript errors
5. Verify that meeting data displays correctly

## Troubleshooting

### Common Issues

**No meetings appear:**
- Verify your data source URL is accessible
- Check that the JSON format is valid
- Ensure CORS headers are set on your data source

**Maps not working:**
- Verify your Google Maps API key is valid
- Check that latitude/longitude values are included in your data
- Ensure the Maps JavaScript API is enabled in Google Cloud Console

**Styling issues:**
- Check that your custom CSS is valid
- Use browser developer tools to debug styling
- Ensure CSS selectors are specific enough

### Debug Mode

Add `?debug=1` to your page URL to see additional debug information in the browser console.