<?php
/**
 * WP_Headless_Rest_Disable_Frontend
 *
 * Disabled the WordPress front end. Front end is disabled by default
 *
 * Configuration:
 *
 * Re-Enable the front end:
 *  add_filter( 'wp_headless_rest__disable_front_end', '__return_false' );
 *
 *
 * @author Ben Moody
 */

class WP_Headless_Rest_Disable_Frontend {

	public static $disable_front_end = true;

	public function __construct() {

		//Maybe enable rest nonce
		add_action(
			'init',
			array(
				$this,
				'maybe_disable_front_end',
			)
		);

	}

	/**
	 * maybe_disable_front_end
	 *
	 * @CALLED BY /ACTION 'init'
	 *
	 * Run process to detect if rest api nonce is enabled
	 *
	 * @example enable with: add_filter( 'wp_headless_rest__enable_rest_nonce',
	 *     '__return_true' );
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function maybe_disable_front_end() {

		/**
		 * wp_headless_rest__enable_rest_nonce
		 *
		 * Allows devs to disabled the front end of wordpress, disabled by default
		 *
		 * @example add_filter( 'wp_headless_rest__disable_front_end', '__return_false' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Disable_Frontend::__construct()
		 *
		 * @param bool
		 */
		self::$disable_front_end = apply_filters( 'wp_headless_rest__disable_front_end', self::$disable_front_end );

		if ( true === self::$disable_front_end ) {

			//Set 404 for all template redirects
			add_action(
				'template_redirect',
				array(
					$this,
					'disabled_front_end',
				)
			);

		}

	}

	/**
	* disabled_front_end
	*
	* @CALLED BY ACTION 'template_redirect'
	*
	* On template redirect (so we know this is a front end render) set headers to 404 and die
	*
	* @access public
	* @author Ben Moody
	*/
	public function disabled_front_end() {

		global $wp_query;

		if ( is_admin() ) {
			return;
		}

		$wp_query->set_404();
		status_header( 404 );

		die();
	}

}

new WP_Headless_Rest_Disable_Frontend();
