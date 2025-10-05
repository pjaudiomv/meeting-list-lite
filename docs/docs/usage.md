---
sidebar_position: 4
---

# Usage

Learn how to use the Meeting List Lite shortcode to display meetings on your WordPress site.

## Basic Shortcode

The simplest way to display meetings is with the basic shortcode:

```
[tsml_ui]
```

This will use the global settings configured in **Settings** → **Meeting List Lite**.

### Quick BMLT Setup

If you're using BMLT Root Server 4.0.0+:

1. **Get your BMLT TSML URL:**
   - Go to: `https://your-bmlt-server.org/main_server/semantic`
   - Build your query (select service body, filters, etc.)
   - Replace `/json/` with `/tsml/` in the generated URL

2. **Configure the plugin:**
   - **Settings** → **Meeting List Lite**
   - Paste your TSML URL in **Data Source URL**
   - Save

3. **Add to page:**
   ```
   [tsml_ui]
   ```

**Example BMLT URL:**
```
https://latest.aws.bmlt.app/main_server/client_interface/tsml/?switcher=GetSearchResults&services=1006
```

## Shortcode Attributes

You can customize the meeting list display by adding attributes to the shortcode.

### Available Attributes

| Attribute | Description | Example |
|-----------|-------------|---------|
| `data_src` | Override the data source URL | `data_src="https://example.com/meetings.json"` |
| `timezone` | Override the timezone | `timezone="America/New_York"` |

### Examples

**Basic shortcode with custom data source:**
```
[tsml_ui data_src="https://mysite.com/meetings.json"]
```

**Shortcode with custom timezone:**
```
[tsml_ui timezone="Europe/London"]
```

**Shortcode with all attributes:**
```
[tsml_ui data_src="https://mysite.com/meetings.json" timezone="America/Los_Angeles"]
```

## Where to Use the Shortcode

### Pages and Posts

Add the shortcode to any WordPress page or post content:

1. Edit the page/post in the WordPress editor
2. Add the `[tsml_ui]` shortcode where you want the meeting list to appear
3. Publish or update the page

### Text Widgets

You can also use the shortcode in text widgets:

1. Go to **Appearance** → **Widgets**
2. Add a **Text** widget to your desired widget area
3. Enter the `[tsml_ui]` shortcode in the widget content
4. Save the widget

### Template Files (Advanced)

For theme developers, you can use the shortcode in template files:

```php
<?php echo do_shortcode('[tsml_ui]'); ?>
```

## Styling the Meeting List

### Default Styling

The plugin includes minimal default styling to ensure the meeting list displays properly. The meeting list will inherit most styles from your theme.

### Custom CSS

Add custom CSS through the plugin settings or your theme's additional CSS:

**Common customizations:**

```css
/* Container styling */
.meetinglistlite-fullwidth {
  background: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
}

/* Meeting list styling */
#tsml-ui {
  font-family: 'Open Sans', Arial, sans-serif;
  max-width: 100%;
}

/* Search and filter styling */
.tsml-search {
  margin-bottom: 20px;
}

.tsml-search input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

/* Meeting card styling */
.tsml-meeting {
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  margin-bottom: 15px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tsml-meeting-name {
  font-weight: bold;
  font-size: 1.1em;
  color: #333;
  margin-bottom: 5px;
}

/* Map styling */
.tsml-map {
  height: 400px;
  border-radius: 6px;
  overflow: hidden;
}
```

### Responsive Design

The TSML UI component is responsive by default, but you may want to add additional mobile styles:

```css
@media (max-width: 768px) {
  .meetinglistlite-fullwidth {
    padding: 10px;
  }
  
  #tsml-ui {
    font-size: 14px;
  }
  
  .tsml-meeting {
    padding: 10px;
  }
}
```

## Meeting List Features

The TSML UI provides several built-in features:

### Search and Filtering

- **Location Search**: Search by city, neighborhood, or location name
- **Day Filter**: Filter meetings by day of the week  
- **Time Filter**: Show meetings by time of day
- **Meeting Type Filter**: Filter by meeting types (Open, Closed, etc.)
- **Region Filter**: Filter by geographic regions

### Map Display

When latitude/longitude coordinates are available:
- Interactive map showing meeting locations
- Click markers to see meeting details
- Zoom and pan functionality
- Mobile-friendly touch controls

### Meeting Details

Each meeting displays:
- Meeting name and location
- Day and time
- Meeting types and formats
- Address (if available)
- Notes and special instructions
- Conference URLs for online meetings

## Multiple Meeting Lists

You can display multiple meeting lists on the same site by using different data sources:

**Page 1 - Local meetings:**
```
[tsml_ui data_src="https://local-area.org/meetings.json"]
```

**Page 2 - Online meetings:**
```
[tsml_ui data_src="https://online-meetings.org/meetings.json"]
```

## Troubleshooting

### Shortcode Not Working

If the shortcode appears as text instead of displaying meetings:

1. Ensure the plugin is activated
2. Check that you're using the correct shortcode: `[tsml_ui]`
3. Verify your data source URL is accessible
4. Check for PHP or JavaScript errors in your browser console

### Empty Meeting List

If the meeting list appears but shows no meetings:

1. Verify your data source URL returns valid JSON
2. Check that the JSON follows the TSML specification
3. Ensure your data source allows CORS requests
4. Check browser network tab for failed requests

### Styling Issues

If the meeting list doesn't look right:

1. Check for theme CSS conflicts
2. Add custom CSS to override theme styles
3. Use browser developer tools to inspect elements
4. Ensure the container has sufficient width

## Performance Tips

- Use a fast, reliable data source
- Enable caching on your data source server
- Minimize the size of your JSON data
- Consider using a CDN for your data source