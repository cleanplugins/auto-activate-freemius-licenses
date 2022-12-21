<?php

/**
 * Plugin Name: Auto Activate Freemius Licenses
 * Plugin URI: https://github.com/cleanplugins/auto-activate-freemius-licenses
 * Description: This plugin allows you to automatically activate your Freemius licenses.
 * Version: 1.0
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Author: Clean Plugins
 * Author URI: https://www.cleanplugins.com/
 **/

// Don't load directly
if (!defined('ABSPATH')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Require the minimum PHP version
if (version_compare(PHP_VERSION, '7.0', '<')) {
    add_action('admin_notices', function () {
        echo '<div class="error notice"><p><b>Auto Activate Freemius Licenses</b> requires at least PHP 7.0.</p></div>';
    });

    return;
}

/**
 * Auto activate licenses defined in wp-config.php file.
 */
add_action('admin_init', function () {
    global $fs_auto_activate_licenses;

    // Check if the licenses are correctly defined in wp-config.php
    if (empty($fs_auto_activate_licenses) || !is_array($fs_auto_activate_licenses)) return;

    // Check if the Freemius SDK is loaded
    if (!class_exists('Freemius')) return;

    // Check if the license activation is not runing already
    if (get_option('fs_auto_activating_licenses')) return;

    // Set the option to avoid the activation to run again in the same/other requests
    update_option('fs_auto_activating_licenses', true);

    foreach ($fs_auto_activate_licenses as $id => $license) {
        // Check if the license is not empty and it's valid
        if (empty($license) || strlen($license) !== 32) continue;

        // Get the Freemius SDK instance for the plugin id
        $fs = Freemius::get_instance_by_id($id);

        // Check if the license is not already activated
        if (is_object($fs) && $fs->has_api_connectivity() && !$fs->is_registered()) {
            // Activate the license
            $result = $fs->activate_migrated_license($license);

            $name = $fs->get_plugin_name();
            $error = $result['error'] ?? '';

            // Check the license activation result
            if ($error) {
                add_action('admin_notices', function () use ($name, $error) {
                    echo '<div class="notice notice-warning is-dismissible">
                        <p><b>' . $name . '</b>: ' . $error . '</p>
                    </div>';
                });
            } else {
                $next_page = $result['next_page'] ?? '';
                add_action('admin_notices', function () use ($name, $next_page) {
                    $next_link = $next_page ? '<a href="' . $next_page . '">Get Started</a>.' : '';

                    echo '<div class="notice notice-success is-dismissible">
                        <p><b>' . $name . '</b>: The defined license key has been activated successfully. ' . $next_link . '</p>
                    </div>';
                });
            }
        }
    }
}, 0);

/**
 * Remove the option to allow the activation to run again in the other requests
 */
add_action('shutdown', function () {
    if (get_option('fs_auto_activating_licenses')) {
        update_option('fs_auto_activating_licenses', false);
    }
}, 0);
