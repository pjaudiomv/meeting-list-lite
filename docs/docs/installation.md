---
sidebar_position: 2
---

# Installation

Learn how to install and set up Meeting List Lite on your WordPress site.

## Download and Install

### Method 1: WordPress Admin Dashboard (Recommended)

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins** → **Add New**
3. Search for "Meeting List Lite"
4. Click **Install Now** on the Meeting List Lite plugin
5. Click **Activate** to enable the plugin

### Method 2: Manual Installation

1. Download the plugin from the [WordPress Plugin Directory](https://wordpress.org/plugins/meeting-list-lite/)
2. Upload the `meeting-list-lite` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the **Plugins** menu in WordPress

### Method 3: Upload ZIP File

1. Download the plugin ZIP file
2. Go to **Plugins** → **Add New** → **Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Click **Activate Plugin**

## Post-Installation Setup

### 1. Configure WordPress Timezone

:::warning Important
This step is crucial for accurate meeting times display.
:::

1. Go to **Settings** → **General** in your WordPress dashboard
2. Set **Timezone** to a valid IANA timezone identifier (e.g., "America/New_York")
3. **Avoid** generic UTC offsets like "UTC+5"
4. See the [complete list of PHP timezones](https://www.php.net/manual/en/timezones.php)

### 2. Access Plugin Settings

1. Navigate to **Settings** → **Meeting List Lite**
2. You'll see the configuration options for your data source

## Verification

After installation, verify the plugin is working:

1. Create a new page or post
2. Add the shortcode: `[tsml_ui]`
3. Publish and view the page
4. You should see the meeting list interface (empty until you configure a data source)

## What's Next?

- [Configure your data source](./configuration.md)
- [Learn about shortcode usage](./usage.md)

## System Requirements

- **WordPress**: 5.3 or higher (tested up to 6.8)
- **PHP**: 8.0 or higher
- **Web Server**: Apache or Nginx
- **HTTPS**: Recommended for external data sources

## Troubleshooting Installation

### Plugin Activation Errors

If you encounter errors during activation:

1. Check that your PHP version meets the minimum requirement (8.0+)
2. Ensure WordPress is up to date
3. Deactivate any conflicting plugins temporarily
4. Check your error logs in **Tools** → **Site Health** → **Info** → **Server**

### Permission Issues

If you can't upload or activate the plugin:

1. Check file permissions on your `/wp-content/plugins/` directory
2. Ensure your hosting provider allows plugin installations
3. Contact your hosting support if needed

### Memory Limit Issues

If you see memory limit errors:

1. Increase PHP memory limit in your hosting control panel
2. Or add this line to your `wp-config.php`: `ini_set('memory_limit', '256M');`