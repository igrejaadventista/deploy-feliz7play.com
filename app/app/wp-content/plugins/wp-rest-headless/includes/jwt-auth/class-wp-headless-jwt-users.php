<?php
/**
 * WP_Headless_Jwt_Users
 *
 * Handles all logic for handling interaction between JWT Auth requests and WP
 * user Auth
 *
 * @author Ben Moody
 */

class WP_Headless_Jwt_Users {

	public function __construct() {

		//Maybe auth user via token
		add_filter(
			'determine_current_user',
			array(
				$this,
				'set_current_user_from_jwt_token',
			)
		);

	}

	/**
	 * authenticate_user
	 *
	 * Helper to authenticate a user account with WP based on username and
	 * password
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return mixed wp_error / user object
	 * @access public
	 * @author Ben Moody
	 */
	public static function authenticate_user( $username, $password ) {

		$error_code = null;
		$output     = null;

		//Try and authenticate user with credentials
		$output = wp_authenticate(
			$username,
			$password
		);

		//Any success with finding user account?
		if ( is_wp_error( $output ) ) {

			$error_code = $output->get_error_code();

			$output = new WP_Error(
				'User Auth Error',
				'Either username or password are incorrect',
				array(
					'status' => 403,
				)
			);

		}

		return $output;
	}

	/**
	 * set_current_user_from_jwt_token
	 *
	 * @CALLED BY FILTER 'determine_current_user'
	 *
	 * Hook into WP before it sets up the user object.
	 * Make sure that the request is to a rest api endpoint, validate the user
	 *     token, pass user ID to WP
	 *
	 * @param int $prev_user_id
	 *
	 * @return int $prev_user_id/$user_id
	 * @access public
	 * @author Ben Moody
	 */
	public function set_current_user_from_jwt_token( $prev_user_id ) {

		$token   = '';
		$user_id = 0;

		//Is this a request to the rest api
		if ( ! WP_Headless_Jwt_Rest_Api::is_request_to_rest_api() ) {
			return $prev_user_id;
		}

		//Is this a Gutenberg request?
		if ( WP_Headless_Jwt_Rest_Api::is_gutenberg_request() ) {
			return $prev_user_id;
		}

		//Is this a request to the get/validate token request
		if ( WP_Headless_Jwt_Rest_Api::is_headless_jwt_request() ) {
			return $prev_user_id;
		}

		//OK this is a rest api request, let's try and set the user based on the token
		$token = WP_Headless_Jwt_Tokens::get_auth_key_from_headers();
		if ( is_wp_error( $token ) ) {
			return $prev_user_id;
		}

		//Try and decode token
		$token = WP_Headless_Jwt_Tokens::decode_token( $token );
		if ( is_wp_error( $token ) ) {
			return $prev_user_id;
		}

		//Does the token contain a user id
		$user_id = WP_Headless_Jwt_Tokens::get_user_id_from_token( $token );
		if ( is_wp_error( $user_id ) ) {
			return $prev_user_id;
		}

		//Alright, we have the user id from the token, give it to WP to setup the user object
		return $user_id;
	}

}

new WP_Headless_Jwt_Users();
