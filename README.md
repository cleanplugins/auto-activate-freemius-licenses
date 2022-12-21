## Auto Activate Freemius Licenses

A WordPress plugin that automatically activates Freemius licenses defined in the wp-config.php file.

## Installation

This tool can be installed as any other WordPress plugin:
1. Copy/Upload the **auto-activate-freemius-licenses** folder to the **/wp-content/plugins/** directory.
2. Activate the plugin through the **Plugins** menu in WordPress.

To install this tool as a must-use plugin, copy/upload the **auto-activate-freemius-licenses.php** file to the **/wp-content/mu-plugins/** directory.

## Configuration

To configure the license keys, add the following global variable to your **wp-config.php** file anywhere before the line `/* That's all, stop editing! Happy publishing. */`:

```php
/* Define Freemius licenses in the format: 'plugin_id' => 'license key' */
$fs_auto_activate_licenses = array(
    '4466' => 'sk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx', // Oxygen Attributes
    '5819' => 'sk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx', // Hydrogen Pack
    '6334' => 'sk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx', // Advanced Scripts
);
```
Make sure to remove the placeholder line of the other plugins you are not using.

## Usage

Once the plugin is activated and configured, your Freemius licenses will be automatically activated when the plugins are loaded.
