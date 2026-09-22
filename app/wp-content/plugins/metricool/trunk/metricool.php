<?php

/**
 * @package Metricool
 * @author Really Simple Plugins
 * @copyright 2026 Really Simple Plugins
 * @license GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Metricool - Social media and site statistics
 * Plugin URI: https://metricool.com/
 * Description: Site metrics and social media analytics for Instagram, Facebook, YouTube, LinkedIn, X Twitter. The single best tool for Social Media Managers!
 * Version: 2.1.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: Really Simple Plugins
 * Author URI: https://really-simple-plugins.com
 * License: GPL v2 or later
 * Text Domain: metricool
 * Domain Path: /assets/languages
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load the Composer autoloader for the plugin's own classes.
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load the scoped (prefixed) dependencies to prevent conflicts
 * with other plugins bundling the same packages.
 * @see https://packagist.org/packages/wpify/scoper
 */
require_once __DIR__ . '/vendor/_scoped/scoper-autoload.php';

// Boot the plugin.
$plugin = new Metricool\Bootstrap\Plugin();
$plugin->boot();
