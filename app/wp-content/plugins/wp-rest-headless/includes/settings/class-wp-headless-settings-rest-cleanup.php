<?php
/**
 * WP_Headless_Settings_Rest_Cleanup
 *
 * Handles all logic for generating the settings Main Option section
 *
 * @author Ben Moody
 */

class WP_Headless_Settings_Rest_Cleanup extends WP_Headless_Settings {

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
			esc_html_x( '', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
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

		return;

	}

}