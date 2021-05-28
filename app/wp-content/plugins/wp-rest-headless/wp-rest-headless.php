<?php
/*
Plugin Name: WP Headless CMS Framework
Description: Plugin sets up WordPress as a headless CMS. Provides JSON Web Token Authentication, Adds Gutenberg block data to REST responses, Protects REST requests with nonce, cleans up REST response objects, removes unrequired REST endpoints, and can disabled the frontend
Author: Benjamin Moody
Version: 1.15
*/

/**
 * Current version of plugin
 */
define( 'WP_REST_HEADLESS_PLUGIN_VERSION', '1.15' );

/**
 * Filesystem path to plugin
 */
define( 'WP_REST_HEADLESS_PLUGIN_BASE_DIR', dirname( __FILE__ ) );
define( 'WP_REST_HEADLESS_PLUGIN_BASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Define min WordPress Version
 */
define( 'WP_REST_HEADLESS_PLUGIN__MINIMUM_WP_VERSION', '5.0.0' );

/**
 * Define plugin text domain
 */
define( 'WP_REST_HEADLESS_TEXT_DOMAIN', 'wp_rest_headless' );

/**
 * wp_rest_headless_boot_plugin
 *
 * CALLED ON ACTION 'after_setup_theme'
 *
 * Includes all class files for plugin, runs on 'after_theme_setup' to allows
 * themes to override some classes/functions
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'plugins_loaded', 'wp_rest_headless_boot_plugin' ); //Allows themes to override classes, functions
function wp_rest_headless_boot_plugin() {

	//vars
	$includes_path = WP_REST_HEADLESS_PLUGIN_BASE_DIR . '/includes';
	$vendors_path  = WP_REST_HEADLESS_PLUGIN_BASE_DIR . '/vendor';

	//Load JWT vendor dependent
	wp_rest_headless_include_file( "{$vendors_path}/autoload.php" );

	//Include plugin core file
	wp_rest_headless_include_file( "{$includes_path}/class-wp-headless-core.php" );

	//Include plugin JWT Auth files
	wp_rest_headless_include_file( "{$includes_path}/jwt-auth/class-wp-headless-jwt-tokens.php" );
	wp_rest_headless_include_file( "{$includes_path}/jwt-auth/class-wp-headless-jwt-rest-api.php" );
	wp_rest_headless_include_file( "{$includes_path}/jwt-auth/class-wp-headless-jwt-users.php" );

	//Include plugin REST API files
	wp_rest_headless_include_file( "{$includes_path}/rest-api/class-wp-headless-rest-api-cors.php" );
	wp_rest_headless_include_file( "{$includes_path}/rest-api/class-wp-headless-rest-api-cleanup.php" );

	//Include plugin SECURITY files
	wp_rest_headless_include_file( "{$includes_path}/security/class-wp-headless-rest-api-nonce.php" );
	wp_rest_headless_include_file( "{$includes_path}/security/class-wp-headless-disable-front-end.php" );

	//Include plugin Gutenberg files
	wp_rest_headless_include_file( "{$includes_path}/gutenberg/class-wp-headless-rest-gutenberg-blocks.php" );

	//Include plugin settings file
	wp_rest_headless_include_file( "{$includes_path}/settings/class-wp-headless-settings.php" );
	wp_rest_headless_include_file( "{$includes_path}/settings/class-wp-headless-settings-request-headers.php" );
	wp_rest_headless_include_file( "{$includes_path}/settings/class-wp-headless-settings-rest-jwt.php" );
	wp_rest_headless_include_file( "{$includes_path}/settings/class-wp-headless-settings-rest-nonce.php" );
	wp_rest_headless_include_file( "{$includes_path}/settings/class-wp-headless-settings-rest-cleanup.php" );

	define( 'WP_REST_HEADLESS_PLUGIN_LOADED', true );

}

/**
 * wp_rest_headless_include_file
 *
 * Helper to test file include validation and include_once if safe
 *
 * @param    string    Path to include
 *
 * @return    mixed    Bool/WP_Error
 * @access    public
 * @author    Ben Moody
 */
function wp_rest_headless_include_file( $path, $require = false ) {

	//Check if a valid path for include
	if ( validate_file( $path ) > 0 ) {

		//Failed path validation
		return new WP_Error(
			'wp_rest_headless_include_file',
			'File include path failed path validation',
			$path
		);

	}

	if ( true === $require ) {

		require_once( $path );

	} else {

		include_once( $path );

	}

	return true;
}

/**
 * wp_rest_headless_get_template_path
 *
 * Helper to return path to plugin template part file
 *
 * NOTE you can override any template file by adding a copy of the file from
 * the plugin 'template-parts' dir into your theme under the
 * 'wp-headless' subdir
 *
 * @param string $slug - template part slug name
 * @param string $template_name - template part filename NO .php
 *
 * @return string $path
 * @access public
 * @author Ben Moody
 */
function wp_rest_headless_get_template_path( $slug, $template_name ) {

	//vars
	$path = WP_REST_HEADLESS_PLUGIN_BASE_DIR . '/template-parts';

	$slug          = $slug;
	$template_name = $template_name;

	//Setup template filenames/paths
	$plugin_template_file_path = "{$path}/{$slug}-{$template_name}.php";
	$theme_template_filename   = "/wp-headless/{$slug}-{$template_name}.php";

	//First try and get theme override template
	$theme_template_file_path = locate_template( array( $theme_template_filename ) );

	if ( '' !== $theme_template_file_path ) {

		$path = $theme_template_file_path;

	} else { //Fallback to plugin's version of template

		$path = $plugin_template_file_path;

	}

	return $path;
}
