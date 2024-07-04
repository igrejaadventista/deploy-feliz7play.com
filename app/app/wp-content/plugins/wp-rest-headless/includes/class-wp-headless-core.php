<?php
/**
 * WP_Headless_Core
 *
 * Handles all logic for core elements of plugin
 *
 * @author Ben Moody
 */

class WP_Headless_Core {

	protected static $plugin_prefix;

	public static $plugin_rest_namespace = 'wp-headless/v1';

	public function __construct() {

		//Set plugin prefix
		self::$plugin_prefix = 'wp_rest_headless_';

		//Set textdomain
		add_action( 'after_setup_theme', array( $this, 'plugin_textdomain' ) );

	}

	/**
	 * Setup plugin textdomain folder
	 *
	 * @public
	 */
	public function plugin_textdomain() {

		load_plugin_textdomain( WP_REST_HEADLESS_TEXT_DOMAIN, false, '/wp-headless/languages/' );

		return;
	}

}

new WP_Headless_Core();
