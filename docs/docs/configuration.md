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

## BMLT Integration

### Using with BMLT Root Server 4.0.0+

The BMLT Root Server version 4.0.0 and later includes built-in TSML-compatible JSON output, making it perfect for use with Meeting List Lite.

**Why use BMLT with Meeting List Lite?**
- ✅ **Real-time data** - Always shows current meeting information
- ✅ **No maintenance** - Your area maintains the data in BMLT
- ✅ **Automatic geocoding** - BMLT handles address validation and coordinates
- ✅ **Format standardization** - Uses NAWS format codes
- ✅ **Service body filtering** - Show only your area's meetings
- ✅ **Built-in TSML compatibility** - No data conversion needed

### Building Your BMLT Query

**Step 1: Use the BMLT Query Builder**

Every BMLT server has a built-in query builder to help you create the perfect URL:

1. Go to your BMLT server's semantic page: `https://your-bmlt-server.org/main_server/semantic`
2. Use the form to select your desired filters:
   - Service bodies (areas/regions)
   - Weekdays
   - Meeting formats
   - Times
   - Search terms
3. The builder will generate a URL like: `https://your-bmlt-server.org/main_server/client_interface/json/?switcher=GetSearchResults&services=1006`

**Step 2: Convert to TSML Format**

Replace `/json/` with `/tsml/` in your generated URL:

```diff
- https://your-bmlt-server.org/main_server/client_interface/json/?switcher=GetSearchResults&services=1006
+ https://your-bmlt-server.org/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006
```

**Step 3: Use in Meeting List Lite**

1. Copy your TSML URL
2. Go to **Settings** → **Meeting List Lite**
3. Paste the URL in the **Data Source URL** field
4. Save settings
5. Add `[tsml_ui]` to any page

### Example BMLT URLs

**All meetings from a BMLT server:**
```
https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults
```

**Specific service body meetings:**
```
https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006
```

**Multiple service bodies:**
```
https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006,1007,1008
```

**Weekday meetings only (Monday-Friday):**
```
https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&weekdays=1,2,3,4,5
```

**Open meetings only:**
```
https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&formats=17
```

### Common BMLT Filters

| Parameter | Description | Example |
|-----------|-------------|----------|
| `services` | Service body ID(s) | `services=1006` or `services=1006,1007` |
| `weekdays` | Days of week (0=Sunday) | `weekdays=1,2,3,4,5` |
| `formats` | Meeting format ID(s) | `formats=17,54` |
| `meeting_keys` | Specific meeting ID(s) | `meeting_keys=12345,12346` |
| `SearchString` | Text search | `SearchString=meditation` |
| `StartsAfterH` | Meetings starting after hour | `StartsAfterH=18` |
| `StartsAfterM` | Meetings starting after minute | `StartsAfterM=30` |
| `EndsBeforeH` | Meetings ending before hour | `EndsBeforeH=21` |
| `EndsBeforeM` | Meetings ending before minute | `EndsBeforeM=0` |

### Finding Your Service Body ID

1. Go to your BMLT server's semantic query builder
2. Look for your area/region in the "Service Bodies" dropdown
3. The ID number will be shown or you can inspect the form to find it
4. Alternatively, contact your area service committee

### BMLT Format Mapping

BMLT automatically maps meeting formats to TSML-compatible codes:

- **Basic Types**: `O` (Open), `C` (Closed)
- **Accessibility**: `WC` (Wheelchair accessible)
- **Study Types**: `STEP` (Step Study), `TRAD` (Traditions), `BEG` (Beginners)
- **Online/Hybrid**: `ONL` (Online), `HY` (Hybrid)
- **Special Formats**: `NS` (Non-smoking), `JFT` (Just for Today)
- **Demographics**: `M` (Men only), `W` (Women only)

### Live Example

See BMLT + Meeting List Lite in action at:
[https://wordpress.aws.bmlt.app/meetings/](https://wordpress.aws.bmlt.app/meetings/)

### Requirements & Limitations

**Requirements:**
- BMLT Root Server 4.0.0 or later
- Your meetings must be maintained in BMLT
- Valid IANA timezone in WordPress settings

**Limitations:**
- Only NAWS format codes are supported (non-NAWS formats are filtered out)
- Some BMLT-specific fields (train, bus, comments) may not display in the TSML UI
- Requires internet connection to fetch meeting data

### Troubleshooting BMLT Integration

**No meetings showing:**
1. Verify your BMLT server URL is correct
2. Check that your service body ID exists
3. Test your URL directly in a browser - you should see JSON data
4. Ensure your BMLT server is version 4.0.0 or later

**Meetings appear but missing information:**
- Some BMLT fields don't map directly to TSML
- Check that your meetings have latitude/longitude coordinates in BMLT for maps

**Format codes not displaying correctly:**
- Only NAWS-compatible format codes will appear
- Custom local formats may be filtered out

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