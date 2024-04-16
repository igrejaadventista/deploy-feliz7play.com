<?php
/**
 * WP_Headless_Rest_Api_Nonce
 *
 * Handle enhancing rest api security with a nonce for all requests
 *
 * Configuration:
 *
 * Enable the rest nonce system:
 *  add_filter( 'wp_headless_rest__enable_rest_nonce', '__return_true' );
 *
 * Disable the rest nonce check (great for dev environments)
 *  add_filter( 'wp_headless_rest__disable_rest_nonce_check', '__return_true'
 * );
 *
 * Generate a nonce key via PHP: wp_headless_rest_generate_rest_nonce()
 *
 * Enable ability to generate nonce keys via rest api request to endpoint
 * '/wp-headless/v1/rest-nonce/generate' add_filter(
 * 'wp_headless_rest__enable_rest_nonce_generator_endpoint', '__return_true' );
 *
 * Set the public key use to authenticate your app for requests to the nonce
 * generator endpoint add_filter( 'wp_headless_rest__rest_nonce_generator_key',
 * function(){ return 'YOUR_PUBLIC_GENERATOR_KEY'; } );
 *
 * Endpoints:
 *
 * Validate a nonce: /wp-headless/v1/rest-nonce/validate
 *  Header = X-WP-NONCE : nonce_to_validate
 *  Returns 200/403
 *
 * Generate a nonce: /wp-headless/v1/rest-nonce/generate
 *  Header = X-WP-NONCE-GENERATOR : your_public_nonce_generator_key (this is
 * set via filter 'wp_headless_rest__rest_nonce_generator_key')
 *
 *
 * @author Ben Moody
 */

class WP_Headless_Rest_Api_Nonce {

	public static $rest_api_generator_route = 'rest-nonce/generate';
	public static $rest_api_validate_route = 'rest-nonce/validate';
	public static $nonce_generator = false;
	public static $enable_rest_api_nonce = false;
	public static $disable_nonce_check = false;
	public static $enable_rest_api_generator = false;

	public function __construct() {

		//Maybe enable rest nonce
		add_action(
			'init',
			array(
				$this,
				'maybe_enable_rest_nonce',
			)
		);

	}

	/**
	 * maybe_enable_rest_nonce
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
	public function maybe_enable_rest_nonce() {

		/**
		 * wp_headless_rest__enable_rest_nonce
		 *
		 * Allows devs to enable to use of a nonce for each rest request, Prevents public access to the rest api outside the app
		 *
		 * @example add_filter( 'wp_headless_rest__enable_rest_nonce', '__return_true' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Nonce::__construct()
		 *
		 * @param bool
		 */
		self::$enable_rest_api_nonce = apply_filters( 'wp_headless_rest__enable_rest_nonce', self::$enable_rest_api_nonce );

		if ( true === self::$enable_rest_api_nonce ) {

			//Prevent external access to ALL REST API endpoints
			add_filter(
				'rest_authentication_errors',
				array(
					$this,
					'restrict_external_rest_access',
				)
			);

		}

		/**
		 * wp_headless_rest__enable_rest_nonce_generator_endpoint
		 *
		 * Allows devs to enable the rest nonce generator endpoint, Allows generation of new rest nonce via rest api
		 *
		 * Note that noce can also be generator via the wp_headless_rest_generate_rest_nonce() function
		 *
		 * @example add_filter( 'wp_headless_rest__enable_rest_nonce_generator_endpoint', '__return_true' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Nonce::__construct()
		 *
		 * @param bool
		 */
		self::$enable_rest_api_generator = apply_filters( 'wp_headless_rest__enable_rest_nonce_generator_endpoint', self::$enable_rest_api_generator );

		if ( true === self::$enable_rest_api_generator ) {

			//Register rest nonce generator custom rest endpoint
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
	 * register_rest_routes
	 *
	 * @CALLED BY ACTION 'rest_api_init'
	 *
	 * Register rest routes for rest nonce
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function register_rest_routes() {

		//Rest nonce generator endpoint
		register_rest_route(
			WP_Headless_Core::$plugin_rest_namespace,
			self::$rest_api_generator_route,
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'generate_nonce_endpoint' ),
			)
		);

		//Rest nonce validation endpoint
		register_rest_route(
			WP_Headless_Core::$plugin_rest_namespace,
			self::$rest_api_validate_route,
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'validate_nonce' ),
			)
		);

	}

	/**
	 * generate_nonce_endpoint
	 *
	 * @CALLED BY register_rest_route callback for /rest-nonce/generate endpoint
	 *
	 * handle request to generate a new rest api nonce via a rest call.
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function generate_nonce_endpoint() {

		$nonce = wp_headless_rest_generate_rest_nonce();

		return rest_ensure_response(
			array(
				'nonce' => $nonce,
			)
		);
	}

	/**
	 * validate_nonce
	 *
	 * @CALLED BY register_rest_route callback for /rest-nonce/validate endpoint
	 *
	 * handle request to validate rest api nonce
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function validate_nonce() {

		$nonce    = null;
		$response = null;

		if ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
			$nonce = $_SERVER['HTTP_X_WP_NONCE'];
		}

		// Check the nonce.
		$result = wp_verify_nonce( $nonce, 'wp_rest' );

		if ( false === $result ) {

			//Error nonce if invalid
			$response = array(
				'result' => false,
				'data'   => array(
					'status' => 403,
				),
			);

		} else {

			//Success token is valid
			$response = array(
				'result' => true,
				'data'   => array(
					'status' => 200,
				),
			);

		}

		return rest_ensure_response( $response );
	}

	/**
	 * restrict_external_rest_access
	 *
	 * @CALLED BY FILTER 'rest_authentication_errors'
	 *
	 * Checks and validates HTTP_X_WP_NONCE in request, prevents requests to
	 *     REST API without valid rest api nonce
	 *
	 * @param bool $result
	 *
	 * @return mixed WP_Error/bool
	 * @access public
	 * @author Ben Moody
	 */
	public function restrict_external_rest_access( $result = null ) {

		/**
		 * wp_headless_rest__disable_rest_nonce_check
		 *
		 * Allows devs to disable the rest api nonce check, great for development environments
		 * Use 'wp_headless_rest__enable_rest_nonce' filter to disable the rest api nonce all together if that's what you want
		 *
		 * @example add_filter( 'wp_headless_rest__disable_rest_nonce_check', '__return_true' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Nonce::restrict_external_rest_access()
		 *
		 * @param bool
		 */
		self::$disable_nonce_check = apply_filters( 'wp_headless_rest__disable_rest_nonce_check', self::$disable_nonce_check );

		//Maybe short circuit the nonce check based on value from 'wp_headless_rest__disable_rest_nonce_check' filter
		if ( true === self::$disable_nonce_check ) {
			return true;
		}

		//Allow requests with JWT authentication to pass without nonce as they will be authenicated via WP
		$request_jwt_token = WP_Headless_Jwt_Tokens::get_auth_key_from_headers();
		if ( ! is_wp_error( $request_jwt_token ) ) {
			return true;
		}
		
		//Allow requests to JWT get-token endpoint
		if(
			isset($_SERVER['REQUEST_URI']) &&
			(false !== strpos($_SERVER['REQUEST_URI'], '/wp-json/wp-headless/v1/jwt/get-token'))
		) {
			return true;
		}

		//Allow requests to the nonce validate endpoint
		if ( 'validate' === self::is_rest_nonce_generator_request() ) {
			return true;
		}

		//Handle requests to the nonce generator endpoint
		if ( true === $this->check_rest_nonce_generator_key() ) {
			return true;
		}

		if ( ! empty( $result ) ) {
			return $result;
		}

		global $wp_rest_auth_cookie;

		/*
		 * Is cookie authentication being used? (If we get an auth
		 * error, but we're still logged in, another authentication
		 * must have been used).
		 */
		if ( true === $wp_rest_auth_cookie && is_user_logged_in() ) {
			return true;
		}

		// Determine if there is a nonce.
		$nonce = null;

		if ( isset( $_REQUEST['_wpnonce'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
		} elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
			$nonce = $_SERVER['HTTP_X_WP_NONCE'];
		}

		// Check the nonce.
		$result = wp_verify_nonce( $nonce, 'wp_rest' );

		if ( ! $result ) {
			return new WP_Error( 'rest_cookie_invalid_nonce', __( 'WP Headless: REST API Cookie nonce is invalid.', WP_REST_HEADLESS_TEXT_DOMAIN ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * is_rest_nonce_generator_request
	 *
	 * Helper to detect if current request is to this plugins rest generator
	 * endpoints
	 *
	 * @return bool name
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_rest_nonce_generator_request() {

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		//Is this a nonce generate request
		if ( false !== strpos( $_SERVER['REQUEST_URI'], self::$rest_api_generator_route ) ) {
			return 'generator';
		}

		//Is this a nonce validate request
		if ( false !== strpos( $_SERVER['REQUEST_URI'], self::$rest_api_validate_route ) ) {
			return 'validate';
		}

		return false;
	}

	/**
	 * check_rest_nonce_generator_key
	 *
	 * @CALLED BY $this->restrict_external_rest_access()
	 *
	 * Check if generator key provided via HTTP_X_WP_NONCE_GENERATOR matches
	 *     that set by dev using 'wp_headless_rest__rest_nonce_generator_key'
	 *     filter. This allows requests to generate a new rest nonce to by-pass
	 *     the rest nonce check
	 *
	 * @var string HTTP_X_WP_NONCE_GENERATOR
	 * @return bool
	 * @access private
	 * @author Ben Moody
	 */
	private function check_rest_nonce_generator_key() {

		//vars
		$key         = false;
		$request_key = false;

		if ( 'generator' !== self::is_rest_nonce_generator_request() ) {
			return false;
		}

		/**
		 * wp_headless_rest__rest_nonce_generator_key
		 *
		 * Allows devs to set a secret key to use in requests to the rest nonce generator endpoint
		 *
		 * @example Visit https://api.wordpress.org/secret-key/1.1/salt/ to generate a random key string
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Nonce::check_rest_nonce_generator_key()
		 *
		 * @param string $key
		 */
		$key = apply_filters( 'wp_headless_rest__rest_nonce_generator_key', $key );

		if ( isset( $_SERVER['HTTP_X_WP_NONCE_GENERATOR'] ) ) {
			$request_key = $_SERVER['HTTP_X_WP_NONCE_GENERATOR'];
		}

		if ( $request_key === $key ) {
			return true;
		}

		return false;
	}

}

new WP_Headless_Rest_Api_Nonce();

/**
 * wp_headless_rest_generate_rest_nonce
 *
 * Helper to return a valid wp_rest nonce
 *
 * @example Place nonce string into header request like this - X-WP-NONCE :
 *     your-nonce-string
 *
 * @return string wp_rest nonce
 * @access public
 * @author Ben Moody
 */
function wp_headless_rest_generate_rest_nonce() {

	return wp_create_nonce( 'wp_rest' );

}
