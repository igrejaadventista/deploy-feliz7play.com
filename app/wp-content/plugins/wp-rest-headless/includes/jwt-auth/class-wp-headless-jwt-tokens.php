<?php
/**
 * WP_Headless_Jwt_Tokens
 *
 * Handles all logic for creating and validating JWT tokens
 *
 * @author Ben Moody
 */

//Require the JWT Library
use \Firebase\JWT\JWT;

class WP_Headless_Jwt_Tokens {

	private static $crypt_type = 'HS256';

	/**
	 * generate
	 *
	 * @CALLED BY callback for register route in
	 *     WP_Headless_Jwt_Rest_Api::register_rest_routes()
	 *
	 * Generate a JWT token for the user account in rest request params
	 *
	 * @param type name
	 *
	 * @var type name
	 * @return type name
	 * @access public
	 * @author Ben Moody
	 */
	public static function generate( $request ) {

		//vars
		$token    = '';
		$response = array();
		$auth_key = self::get_auth_key();

		//Confirm we have a valid auth key to work with
		if ( is_wp_error( $auth_key ) ) {
			return rest_ensure_response( $auth_key );
		}

		//Try and authenticate user with credentials
		$user = WP_Headless_Jwt_Users::authenticate_user(
			$request->get_param( 'username' ),
			$request->get_param( 'password' )
		);

		//Any success with finding user account?
		if ( is_wp_error( $user ) ) {
			return rest_ensure_response( $user );
		}

		//User account is authenticated, create a JWT token
		$token = self::generate_user_token( $user );

		if ( is_wp_error( $token ) ) {
			return rest_ensure_response( $token );
		}

		//Form response
		$response = array(
			'token' => $token,
			'user'  => array(
				'user_email'    => $user->data->user_email,
				'user_nicename' => $user->data->user_nicename,
				'display_name'  => $user->data->display_name,
			),
		);

		/**
		 * wp_headless_rest__generate_jwt_response
		 *
		 * Filter the JWT generate response
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Jwt_Tokens::generate()
		 *
		 * @param array $response
		 * @param string $token
		 * @param object $user
		 */
		$response = apply_filters( 'wp_headless_rest__generate_jwt_response', $response, $token, $user );

		return rest_ensure_response( $response );
	}

	/**
	 * get_auth_key
	 *
	 * Helper to detect and return the WP AUTH_KEY constant
	 *
	 * @return mixed wp_error/string
	 * @access public static
	 * @author Ben Moody
	 */
	public static function get_auth_key() {

		if ( ! defined( 'AUTH_KEY' ) ) {

			//No WP Auth key set!
			return new WP_Error(
				'WP_Headless::auth_key_error',
				'WordPress Auth key not set in wp-config!',
				array(
					'status' => 403,
				)
			);

		}

		if ( empty( AUTH_KEY ) ) {

			//No WP Auth key set!
			return new WP_Error(
				'WP_Headless::auth_key_error',
				'WordPress Auth key not set in wp-config!',
				array(
					'status' => 403,
				)
			);

		}

		return AUTH_KEY;
	}

	/**
	 * generate_user_token
	 *
	 * Generates a valid JWT token for the user account provided via $user
	 * object
	 *
	 * @param object $user
	 *
	 * @return mixed wp_error/string
	 * @access private static
	 * @author Ben Moody
	 */
	private static function generate_user_token( $user ) {

		//vars
		$now          = time();
		$expires      = $now + ( DAY_IN_SECONDS * 7 ); //Valid for 7 days
		$defaults     = array(
			'time_issued'      => $now,
			'not_valid_before' => $now,
			'expires'          => $expires,
		);
		$params       = array(); //Easy to read token params
		$token_params = array();
		$the_token    = null;

		//Valid user account object?
		if ( ! isset( $user->data->ID ) ) {

			//Invalid user account object
			return new WP_Error(
				'WP_Headless::user_object_error',
				'Invalid WordPress User object',
				array(
					'status' => 403,
				)
			);

		}

		/**
		 * wp_headless_rest__pre_token_params
		 *
		 * Filter the base token params with human readable keys
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Jwt_Tokens::generate_user_token()
		 *
		 * @param array $defaults
		 * @param object $user
		 */
		$params = apply_filters( 'wp_headless_rest__pre_token_params', $defaults, $user );
		$params = wp_parse_args( $params, $defaults );

		//Setup final token params using JWT Class param names
		$token_params = array(
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $params['time_issued'],
			'nbf'  => $params['not_valid_before'],
			'exp'  => $params['expires'],
			'data' => array(
				'user' => array(
					'id' => $user->data->ID,
				),
			),
		);

		/**
		 * wp_headless_rest__token_params
		 *
		 * Filter the token params passed to the JWT encode method
		 *
		 * https://github.com/firebase/php-jwt
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Jwt_Tokens::generate_user_token()
		 *
		 * @param array $token_params
		 * @param object $user
		 */
		$token_params = apply_filters( 'wp_headless_rest__token_params', $token_params, $user );

		//Sign the token
		$the_token = JWT::encode( $token_params, self::get_auth_key() );

		return $the_token;
	}

	/**
	 * validate
	 *
	 * @CALLED BY callback for register route in
	 *     WP_Headless_Jwt_Rest_Api::register_rest_routes()
	 *
	 * Validate the token provided in the HTTP_AUTHORIZATION of the request
	 *
	 * @return mixed wp_error/array
	 * @access public static
	 * @author Ben Moody
	 */
	public static function validate( $request ) {

		//vars
		$response      = array();
		$auth_token    = null;
		$auth_key      = self::get_auth_key();
		$decoded_token = array();

		//Confirm we have a valid auth key to work with
		if ( is_wp_error( $auth_key ) ) {
			return rest_ensure_response( $auth_key );
		}

		$auth_token = self::get_auth_key_from_headers( $request );

		//did we get the token?
		if ( is_wp_error( $auth_token ) ) {
			return rest_ensure_response( $auth_token );
		}

		//decode the token
		$decoded_token = self::decode_token( $auth_token );

		if ( is_wp_error( $decoded_token ) ) {
			return rest_ensure_response( $decoded_token );
		}

		//Confirm token is from this site
		$valid_token = self::is_token_from_this_site( $decoded_token );
		if ( is_wp_error( $valid_token ) ) {
			return rest_ensure_response( $auth_key );
		}

		//Does the token contain a user id
		$user_id = self::get_user_id_from_token( $decoded_token );
		if ( is_wp_error( $user_id ) ) {
			return rest_ensure_response( $user_id );
		}

		//Success token is valid
		$response = array(
			'result' => true,
			'data'   => array(
				'status' => 200,
			),
		);

		return rest_ensure_response( $response );
	}

	/**
	 * get_auth_key_from_headers
	 *
	 * Helper to get the user auth key from the request headers
	 *
	 * @param mixed $request
	 *
	 * @return mixed wp_error/string
	 * @access public static
	 * @author Ben Moody
	 */
	public static function get_auth_key_from_headers( $request = false ) {

		//vars
		$auth_header_token = '';

		//Should we get the data from a REST API request?
		if ( is_object( $request ) ) {

			//Try and get auth data from request header
			$auth_header_token = $request->get_header( 'authorization' );

		} else {

			$auth_header_token = isset( $_SERVER['HTTP_AUTHORIZATION'] ) ? $_SERVER['HTTP_AUTHORIZATION'] : false;

			//Maybe fallback to redirect header
			if ( false === $auth_header_token ) {
				$auth_header_token = isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : false;
			}
		}

		if ( empty( $auth_header_token ) || ( false === $auth_header_token ) ) {

			//Invalid user account object
			return new WP_Error(
				'WP_Headless::authorization_header_missing',
				'Can not find the user to in authorization header. Ensure your web server supports the HTTP Authorization Header',
				array(
					'status' => 403,
				)
			);

		}

		return $auth_header_token;
	}

	/**
	 * decode_token
	 *
	 * Helper to decode the JWT token
	 *
	 * @param string $token
	 *
	 * @return mixed wp_error/array
	 * @access public static
	 * @author Ben Moody
	 */
	public static function decode_token( $token ) {

		$output   = '';
		$auth_key = self::get_auth_key();

		//Confirm we have a valid auth key to work with
		if ( is_wp_error( $auth_key ) ) {
			return rest_ensure_response( $auth_key );
		}

		//Lets open up that token!
		try {

			$decoded_token = JWT::decode( $token, $auth_key, array( self::$crypt_type ) );

			$output = $decoded_token;

		} catch ( Exception $e ) {

			return new WP_Error(
				'WP_Headless::invalid_token_error',
				'The provided token is invalid',
				array(
					'status' => 403,
				)
			);

		}

		return $output;
	}

	/**
	 * is_token_from_this_site
	 *
	 * Helper to check if token is from this site
	 *
	 * @param string $token
	 *
	 * @return mixed wp_error/bool
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_token_from_this_site( $token ) {

		//vars
		$site_url = get_bloginfo( 'url' );
		$error    = new WP_Error(
			'WP_Headless::invalid_token_error',
			'The provided token is invalid',
			array(
				'status' => 403,
			)
		);

		if ( ! isset( $token->iss ) ) {
			return $error;
		}

		if ( $site_url !== $token->iss ) {
			return $error;
		}

		return true;
	}

	/**
	 * get_user_id_from_token
	 *
	 * Helper to get user id from token
	 *
	 * @param string $token
	 *
	 * @return mixed wp_error/user_id
	 * @access public static
	 * @author Ben Moody
	 */
	public static function get_user_id_from_token( $token ) {

		//vars
		$error = new WP_Error(
			'WP_Headless::invalid_token_error',
			'The provided token is invalid',
			array(
				'status' => 403,
			)
		);

		if ( ! isset( $token->data->user->id ) ) {
			return $error;
		}

		return intval( $token->data->user->id );
	}

}
