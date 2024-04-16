<?php
/**
 * WP_Headless_Rest_Api_Cors
 *
 * Handle setting up headers for CORS
 *
 * Configuration:
 *
 * Disable headers:
 *  add_filter( 'wp_headless_rest__disable_cors', '__return_true' );
 *
 * Filter header rules:
 *  add_filter( 'wp_headless_rest__cors_rules', array() );
 *
 * @author Ben Moody
 */

class WP_Headless_Rest_Api_Cors {

	public static $cors_rules;
	public static $disable = false;

	public function __construct() {

		add_action(
			'init',
			array(
				$this,
				'setup_rest_api_header_rules',
			)
		);

		//Filter rest pre serve request and generate out custom headers
		add_filter(
			'rest_pre_serve_request',
			array(
				$this,
				'generate_cors_headers',
			)
		);

	}

	/**
	 * setup_rest_api_header_rules
	 *
	 * @CALLED BY ACTION 'rest_api_init'
	 *
	 * Add cors headers for rest api. Restrict to GET, POST methods by default
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	public function setup_rest_api_header_rules() {

		/**
		 * wp_headless_rest__disable_cors
		 *
		 * Allow devs to disable the custom cors rules and keep rest api defaults
		 *
		 * WARNING you will require the correct Access-Control-Allow-Headers for JWT auth to work:
		 *
		 * Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Authorization
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Cors::setup_rest_api_header_rules()
		 *
		 * @param bool $disable_custom_rules
		 */
		self::$disable = apply_filters( 'wp_headless_rest__disable_cors', self::$disable );

		if ( true === self::$disable ) {
			return;
		}

		//vars
		self::$cors_rules = array();
		$default_rules    = array();
		$http_origin      = get_http_origin();
		$origin           = esc_url_raw( site_url() );

		if ( $http_origin ) {
			$origin = esc_url_raw( $http_origin );
		}

		//Remove default rest cors headers
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );

		//Setup default rules
		$default_rules = array(
			'Access-Control-Allow-Origin'      => $origin,
			'Access-Control-Allow-Methods'     => 'GET, POST',
			'Access-Control-Allow-Credentials' => 'true',
			'Access-Control-Allow-Headers'     => 'Access-Control-Allow-Headers, Content-Type, Authorization, origin, x-wp-nonce, x-wp-nonce-generator',
			'Access-Control-Expose-Headers'    => array( 'Link', false ), //Use array if replace param is required
		);

		/**
		 * wp_headless_rest__cors_rules
		 *
		 * Allows devs to filter the rules used for CORS headers
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Cors::setup_rest_api_headers()
		 *
		 * @param array $rules
		 */
		self::$cors_rules = apply_filters( 'wp_headless_rest__cors_rules', $default_rules );

	}

	/**
	* generate_cors_headers
	*
	* @CALLED BY FILTER 'rest_pre_serve_request'
	*
	* Add raw HTTP Headers into rest request response
	*
	* @param bool $served
	* @return bool $served
	* @access public
	* @author Ben Moody
	*/
	public function generate_cors_headers( $served ) {

		if ( ! is_array( self::$cors_rules ) ) {
			return $served;
		}

		//Loop rules and create header item for each
		foreach ( self::$cors_rules as $key => $rule ) {

			if ( is_array( $rule ) ) {

				if ( isset( $rule[0], $rule[1] ) ) {

					header( "{$key}: {$rule[0]}", $rule[1] );

				}
			} else {

				header( "{$key}: {$rule}" );

			}
		}

		return $served;
	}

}

new WP_Headless_Rest_Api_Cors();
