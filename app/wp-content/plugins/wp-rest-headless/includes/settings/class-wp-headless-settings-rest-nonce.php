<?php
/**
 * WP_Headless_Settings_Rest_Nonce
 *
 * Handles all logic for generating the settings Main Option section
 *
 * @author Ben Moody
 */

class WP_Headless_Settings_Rest_Nonce extends WP_Headless_Settings {

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
			esc_html_x( 'REST Routes', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
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
				<strong>Validate Nonce</strong> : <?php echo esc_html( $rest_prefix . WP_Headless_Rest_Api_Nonce::$rest_api_validate_route ); ?>
				<h4>Required POST Parameters:</h4>
				<ul style="margin-left: 20px">
					<li>X-WP-NONCE : NONCE_TO_VALIDATE</li>
				</ul>
				<hr>
			</li>

			<li>
				<strong>Generate Nonce</strong> : <?php echo esc_html( $rest_prefix . WP_Headless_Rest_Api_Nonce::$rest_api_generator_route ); ?>
				<h4>Required POST Parameters:</h4>
				<ul style="margin-left: 20px">
					<li>X-WP-NONCE-GENERATOR : PUBLIC_NONCE_GENERATOR_KEY (see filter 'wp_headless_rest__rest_nonce_generator_key')</li>
				</ul>
				<hr>
			</li>

		</ul>
		<?php

	}

}