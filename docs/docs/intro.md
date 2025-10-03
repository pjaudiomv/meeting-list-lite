---
sidebar_position: 1
---

# Getting Started

Welcome to **Meeting List Lite** – a streamlined WordPress plugin for displaying 12-step meeting information when your meeting data is maintained elsewhere.

## Overview

Meeting List Lite is a streamlined solution for displaying 12-step meeting information on your WordPress site when your meeting data is maintained elsewhere. Unlike the full 12 Step Meeting List plugin, this "lite" version doesn't include a database or editing capabilities—it simply displays meetings from your existing data source.

## Perfect For

Service bodies who:
- Maintain their meeting data in a Google Sheet, JSON feed, or another system
- Don't need to edit meeting information within WordPress
- Want the familiar TSML UI interface without the overhead of data management
- Already have geocoded meeting data (latitude/longitude for in-person meetings)

## Key Features

- **TSML UI Integration**: Displays meetings using the same TSML UI interface as 12 Step Meeting List
- **Multiple Data Sources**: Supports JSON feeds and Google Sheets that follow the [TSML spec](https://github.com/code4recovery/spec)
- **No Database Required**: Your data source is the single source of truth
- **Lightweight**: Easy to set up with minimal overhead
- **TSML Compatible**: Fully compatible with the TSML data format

## Requirements

- **WordPress**: 6.8 or higher (tested up to)
- **PHP**: 8.0 or higher
- **Data Source**: Must follow the [TSML specification](https://github.com/code4recovery/spec)
- **Geocoding**: For in-person meetings, latitude and longitude coordinates must already be included in your data (this plugin does not perform geocoding)

:::info Important
Your WordPress timezone should be set to a valid IANA timezone identifier in Settings → General. See the [PHP timezone list](https://www.php.net/manual/en/timezones.php) for valid values. Generic UTC offsets (like "UTC+5") are not sufficient. You can also override the timezone using the `timezone` shortcode attribute.
:::

## Next Steps

1. [Install the plugin](./installation.md)
2. [Configure your data source](./configuration.md)
3. [Learn how to use the shortcode](./usage.md)
