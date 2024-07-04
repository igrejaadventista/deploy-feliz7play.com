<?php
/**
 * WP_Headless_Settings_Rest_Jwt
 *
 * Handles all logic for generating the settings Main Option section
 *
 * @author Ben Moody
 */

class WP_Headless_Settings_Rest_Jwt extends WP_Headless_Settings {

	/**
	 * create_fields
	 *
	 * @CALLED BY WP_Headless_Settings::create_tab__main_options()
	 *
	 * Init the creation of fields for this page
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function create_fields() {

		//Register post types list
		$this->register_field__post_types();

	}

	/**
	 * register_field__post_types
	 *
	 * @CALLED BY $this->create_fields()
	 *
	 * Add field to WP settings API, this is a html list not a field so we are
	 *     not registering the settings as there is nothing to save in options
	 *     table
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function register_field__post_types() {

		//vars
		$field_slug = self::$plugin_prefix . 'rest-jwt';

		//Register field
		add_settings_field(
			$field_slug,
			esc_html_x( 'JWT REST Routes', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
			array( $this, 'render_list' ),
			$this->menu_slug,
			$this->settings_section_id
		);

	}

	/**
	 * render_list
	 *
	 * @CALLED BY register_field__manual_trigger() :: add_settings_field
	 *     callback
	 *
	 * Renders the list of registered request headers
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function render_list() {

		$rest_prefix = get_rest_url( '', WP_Headless_Core::$plugin_rest_namespace . '/' );

		?>
		<ul>

			<li>
				<strong>Validate Tokens</strong> : <?php echo esc_html( $rest_prefix . WP_Headless_Jwt_Rest_Api::$jwt_validate_route ); ?>
				<h4>Required Request HEADERS:</h4>
				<ul style="margin-left: 20px">
					<li>Content-Type : application/json</li>
					<li>accept : application/json</li>
					<li>Authorization : YOUR_JWT_TOKEN_FROM_GENERATE_ENDPOINT</li>
				</ul>
				<hr>
			</li>

			<li>
				<strong>Generate Token</strong> : <?php echo esc_html( $rest_prefix . WP_Headless_Jwt_Rest_Api::$jwt_get_route ); ?>
				<h4>Required POST Parameters:</h4>
				<ul style="margin-left: 20px">
					<li>username : USERNAME_TO_AUTH</li>
					<li>password : PASSWORD_TO_AUTH</li>
				</ul>
				<hr>
			</li>

		</ul>
		<?php

	}

}