<?php
/**
 * WP_Headless_Jwt_Rest_Api
 *
 * Handles all logic for handling interaction between JWT Auth and WP REST Api
 *
 * @author Ben Moody
 */

class WP_Headless_Jwt_Rest_Api {
	
	public static $jwt_get_route = 'jwt/get-token';
	public static $jwt_validate_route = 'jwt/validate-token';
	public static $enable_jwt = false;

	public function __construct() {

		//Maybe enable rest nonce
		add_action(
			'init',
			array(
				$this,
				'maybe_enable_jwt',
			)
		);

	}

	/**
	 * maybe_enable_jwt
	 *
	 * @CALLED BY /ACTION 'init'
	 *
	 * Run process to detect if jwt is enabled
	 *
	 * @example enable with: add_filter( 'wp_headless_rest__enable_jwt',
	 *     '__return_true' );
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function maybe_enable_jwt() {

		/**
		 * wp_headless_rest__enable_jwt
		 *
		 * Allows devs to enable to use JWT authentication
		 *
		 * @example add_filter( 'wp_headless_rest__enable_jwt', '__return_true' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Nonce::__construct()
		 *
		 * @param bool
		 */
		self::$enable_jwt = apply_filters( 'wp_headless_rest__enable_jwt', self::$enable_jwt );

		if ( true === self::$enable_jwt ) {

			//Register JWT Auth custom rest endpoint
			add_action(
				'rest_api_init',
				array(
					$this,
					'register_rest_routes',
				)
			);

		}

	}

	/**
	 * is_request_to_rest_api
	 *
	 * Helper to detect if current request is via a rest api URL
	 *
	 * @return bool name
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_request_to_rest_api() {

		//vars
		$rest_prefix = rest_get_url_prefix();

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		if ( ! strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ) {
			return false;
		}

		return true;
	}

	/**
	 * is_validate_request
	 *
	 * Helper to detect if current request is to the validate token route
	 *
	 * @return bool name
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_validate_request() {

		//vars
		$rest_prefix = self::$jwt_validate_route;

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		if ( ! strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ) {
			return false;
		}

		return true;

	}

	/**
	 * is_get_token_request
	 *
	 * Helper to detect if current request is to the get token route
	 *
	 * @return bool name
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_get_token_request() {

		//vars
		$rest_prefix = self::$jwt_get_route;

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		if ( ! strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ) {
			return false;
		}

		return true;

	}

	/**
	 * is_headless_jwt_request
	 *
	 * Helper to detect if current request is to this plugins jwt endpoints
	 *
	 * @return bool name
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_headless_jwt_request() {

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		if (
			( ! strpos( $_SERVER['REQUEST_URI'], self::$jwt_get_route ) ) &&
			( ! strpos( $_SERVER['REQUEST_URI'], self::$jwt_validate_route ) )
		) {
			return false;
		}

		return true;

	}

	/**
	 * register_rest_routes
	 *
	 * @CALLED BY ACTION 'rest_api_init'
	 *
	 * Register rest routes for JWT user authentication
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function register_rest_routes() {

		register_rest_route(
			WP_Headless_Core::$plugin_rest_namespace,
			self::$jwt_get_route,
			array(
				'methods'  => 'POST',
				'callback' => array( 'WP_Headless_Jwt_Tokens', 'generate' ),
				'args'     => array(
					'username' => array(
						'required'    => true,
						'description' => esc_html_x( 'Username for account you wish to authenticate', 'rest arg description', WP_REST_HEADLESS_TEXT_DOMAIN ),
						'type'        => 'string',
					),
					'password' => array(
						'required'    => true,
						'description' => esc_html_x( 'Password for account you wish to authenticate', 'rest arg description', WP_REST_HEADLESS_TEXT_DOMAIN ),
						'type'        => 'string',
					),
				),
			)
		);

		register_rest_route(
			WP_Headless_Core::$plugin_rest_namespace,
			self::$jwt_validate_route,
			array(
				'methods'  => 'POST',
				'callback' => array( 'WP_Headless_Jwt_Tokens', 'validate' ),
			)
		);

	}

	/**
	 * is_gutenberg_request
	 *
	 * Helper to detect if current request is from the Gutenberg editor
	 *
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_gutenberg_request() {

		global $pagenow;

		if ( isset( $pagenow ) && ( 'post-new.php' === $pagenow ) ) {
			return true;
		}

		if ( isset( $_REQUEST['_locale'] ) ) {
			return true;
		}

		if ( isset( $_REQUEST['action'] ) && ( 'edit' === $_REQUEST['action'] ) ) {
			return true;
		}

		if ( isset( $_REQUEST['action'] ) && ( 'acf/ajax/render_block_preview' === $_REQUEST['action'] ) ) {
			return true;
		}

		if ( isset( $_REQUEST['context'] ) && ( 'edit' === $_REQUEST['context'] ) ) {
			return true;
		}

		return false;
	}

}

new WP_Headless_Jwt_Rest_Api();
