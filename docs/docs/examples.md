---
sidebar_position: 6
---

# Examples

Practical examples of using Meeting List Lite in different scenarios.

## Basic Meeting Display

### Using Custom JSON

For non-BMLT data sources:

**1. Configure Plugin Settings**
- Go to **Settings** → **Meeting List Lite**
- Set **Data Source URL**: `https://your-site.com/meetings.json`
- Save settings

**2. Create a Page**
Create a new page called "Meetings" and add:
```
[tsml_ui]
```

**3. Result**
Your meetings will display with search, filters, and maps (if coordinates are provided).


### Using BMLT

How to get started with a BMLT server:

**1. Build your BMLT URL:**
- Go to your BMLT server's semantic page: `https://your-bmlt-server.org/main_server/semantic`
- Select your filters (service body, days, etc.)
- Copy the generated URL and replace `/json/` with `/tsml/`

**2. Configure plugin:**
- Go to **Settings** → **Meeting List Lite**
- Enter your BMLT TSML URL
- Save settings

**3. Add shortcode:**
```
[tsml_ui]
```

**Example BMLT setup:**
```
BMLT URL: https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006
```

## BMLT Examples

### Service Body Filtering

Show meetings from specific BMLT service bodies:

**Single Service Body:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006"]
```

**Multiple Service Bodies:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006,1007,1008"]
```

**Different Areas on Different Pages:**

**Page 1: Brooklyn Area**
```
[tsml_ui data_src="https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006"]
```

**Page 2: Manhattan Area**
```
[tsml_ui data_src="https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1007"]
```

### BMLT Format Filtering

**Open Meetings Only:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&formats=17"]
```

**Step Study Meetings:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&formats=54"]
```

**Online Meetings:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&formats=VM"]
```

### BMLT Time-Based Filtering

**Evening Meetings (after 6 PM):**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&StartsAfterH=18"]
```

**Weekday Meetings Only:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&weekdays=1,2,3,4,5"]
```

**Weekend Meetings:**
```
[tsml_ui data_src="https://your-bmlt.org/main_server/client_interface/tsml/?switcher=GetSearchResults&weekdays=0,6"]
```

## Multiple Meeting Lists

Display different meeting lists on different pages:

### Area-Specific Meetings
**Page 1: Downtown Meetings**
```
[tsml_ui data_src="https://api.example.com/downtown-meetings.json"]
```

**Page 2: Suburban Meetings**
```
[tsml_ui data_src="https://api.example.com/suburban-meetings.json"]
```

### Format-Specific Meetings
**Page 1: In-Person Meetings**
```
[tsml_ui data_src="https://api.example.com/in-person-meetings.json"]
```

**Page 2: Online Meetings**
```
[tsml_ui data_src="https://api.example.com/online-meetings.json"]
```

## Google Sheets Integration

### Step 1: Create Your Google Sheet
Create a sheet with these columns:
- name
- day (0=Sunday, 1=Monday, etc.)
- time (HH:MM format)
- location
- address
- latitude
- longitude
- types (comma-separated)
- notes

### Step 2: Publish the Sheet
1. Go to **File** → **Share** → **Publish to web**
2. Choose **Comma-separated values (.csv)**
3. Copy the published URL

### Step 3: Convert to JSON
Use a service to convert CSV to JSON, or set up a converter script.

### Step 4: Use in Plugin
```
[tsml_ui data_src="https://your-converted-json-url.com/meetings.json"]
```

## Timezone Examples

### Different Timezones for Different Pages

**East Coast Meetings:**
```
[tsml_ui data_src="https://east.example.com/meetings.json" timezone="America/New_York"]
```

**West Coast Meetings:**
```
[tsml_ui data_src="https://west.example.com/meetings.json" timezone="America/Los_Angeles"]
```

**International Meetings:**
```
[tsml_ui data_src="https://uk.example.com/meetings.json" timezone="Europe/London"]
```

## Custom Styling Examples

### Basic Styling
Add to your theme's CSS or plugin settings:

```css
.meetinglistlite-fullwidth {
  background: #f8f9fa;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

#tsml-ui {
  font-family: 'Georgia', serif;
}
```

### Meeting Card Styling
```css
.tsml-meeting {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.tsml-meeting-name {
  font-size: 1.3em;
  font-weight: bold;
  margin-bottom: 10px;
}
```

### Dark Theme
```css
.dark-theme .meetinglistlite-fullwidth {
  background: #2d3748;
  color: #e2e8f0;
}

.dark-theme .tsml-meeting {
  background: #4a5568;
  border: 1px solid #718096;
}

.dark-theme .tsml-search input {
  background: #4a5568;
  color: #e2e8f0;
  border: 1px solid #718096;
}
```

## Data Format Examples

### Complete Meeting Example
```json
[
  {
    "name": "Morning Meditation Group",
    "day": 1,
    "time": "07:00",
    "location": "Community Center",
    "address": "123 Main St, Anytown, ST 12345",
    "latitude": 40.7128,
    "longitude": -74.0060,
    "region": "Downtown",
    "types": ["M", "TC", "O"],
    "notes": "Enter through side door. Coffee available.",
    "conference_url": "",
    "conference_phone": ""
  }
]
```

### Online Meeting Example
```json
[
  {
    "name": "Online Recovery Meeting",
    "day": 2,
    "time": "19:00",
    "location": "Zoom Meeting",
    "address": "",
    "latitude": null,
    "longitude": null,
    "region": "Online",
    "types": ["O", "ONL"],
    "notes": "Meeting ID: 123-456-789, Password: recovery",
    "conference_url": "https://zoom.us/j/123456789?pwd=abc123",
    "conference_phone": "+1-646-558-8656"
  }
]
```

### Hybrid Meeting Example
```json
[
  {
    "name": "Hybrid Support Group",
    "day": 3,
    "time": "18:30",
    "location": "Unity Church",
    "address": "456 Oak Ave, Somewhere, ST 54321",
    "latitude": 41.8781,
    "longitude": -87.6298,
    "region": "North Side",
    "types": ["O", "HY"],
    "notes": "In-person and online. Zoom link in description.",
    "conference_url": "https://us02web.zoom.us/j/987654321",
    "conference_phone": "+1-312-626-6799"
  }
]
```

## Widget Implementation

### Sidebar Widget
Add to your theme's sidebar:

1. Go to **Appearance** → **Widgets**
2. Add a **Text** widget
3. Enter title: "Meetings"
4. Add content:
```html
<div style="max-height: 400px; overflow-y: auto;">
[tsml_ui data_src="https://example.com/meetings.json"]
</div>
```

### Footer Widget
```html
<h3>Find a Meeting</h3>
[tsml_ui data_src="https://example.com/meetings.json"]
```

## Advanced Customization

### Custom TSML UI Configuration
Hide certain meeting types and customize strings:

In plugin settings, TSML UI Configuration:
```json
{
  "strings": {
    "en": {
      "search": "Find meetings...",
      "type_descriptions": {
        "C": null,
        "TC": "Temporarily Closed"
      }
    }
  },
  "hide": ["C"],
  "theme": {
    "primary_color": "#007cba"
  }
}
```

### Meeting Type Filtering
Show only specific meeting types by filtering your data source, or use custom JSON configuration.

## Performance Examples

### Cached JSON Endpoint
Set up your data source with caching headers:
```http
Cache-Control: public, max-age=3600
```

### CDN Implementation
```
[tsml_ui data_src="https://cdn.example.com/meetings.json"]
```

### Compressed Data
Ensure your server sends compressed JSON:
```http
Content-Encoding: gzip
```

## Troubleshooting Examples

### Debug Mode
Add to any page to see debug information:
```
[tsml_ui data_src="https://example.com/meetings.json"]?debug=1
```

### CORS Testing
Test your data source directly:
```bash
curl -H "Origin: https://yourwordpresssite.com" \
     -H "Access-Control-Request-Method: GET" \
     https://your-data-source.com/meetings.json
```

### JSON Validation
Validate your JSON format:
1. Copy your JSON data
2. Visit [JSONLint](https://jsonlint.com/)
3. Paste and validate

## Integration Examples

### With Membership Plugins
For restricted content, ensure your data source is accessible:
```php
// In your theme's functions.php
add_action('wp', function() {
    if (is_user_logged_in()) {
        // Show members-only meetings
        add_filter('meetinglistlite_data_src', function() {
            return 'https://members.example.com/meetings.json';
        });
    }
});
```

### With Multilingual Plugins
Different data sources for different languages:
```php
// WPML integration example
add_filter('meetinglistlite_data_src', function($url) {
    $lang = ICL_LANGUAGE_CODE;
    return "https://api.example.com/{$lang}/meetings.json";
});
```

## Testing Your Setup

### Basic Test Checklist
1. ✅ Plugin activated
2. ✅ Data source URL configured
3. ✅ WordPress timezone set correctly
4. ✅ Shortcode added to page
5. ✅ Meetings display without errors
6. ✅ Search and filters work
7. ✅ Maps display (if coordinates provided)

### Advanced Testing
1. Check browser console for JavaScript errors
2. Verify data source returns valid JSON
3. Test on mobile devices
4. Check loading performance
5. Verify GDPR compliance (if applicable)

These examples should help you implement Meeting List Lite in various scenarios. For more specific use cases, consult the [FAQ](./faq.md) or create a support topic.