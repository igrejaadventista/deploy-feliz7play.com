<?php
/**
 * WP_Headless_Settings_Request_Headers
 *
 * Handles all logic for generating the settings Main Option section
 *
 * @author Ben Moody
 */

class WP_Headless_Settings_Request_Headers extends WP_Headless_Settings {

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
		$field_slug = self::$plugin_prefix . 'request-headers';

		//Register field
		add_settings_field(
			$field_slug,
			esc_html_x( 'Current Allowed request headers', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
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

		if ( is_array( WP_Headless_Rest_Api_Cors::$cors_rules ) && ! empty( WP_Headless_Rest_Api_Cors::$cors_rules ) ) {

			?>
			<ul>
				<?php foreach ( WP_Headless_Rest_Api_Cors::$cors_rules as $rule => $value ) : if(is_array($value)){ continue; } ?>
					<li>
						<strong>
							<?php echo esc_html($rule); ?>
						</strong> :
						<?php echo esc_html($value); ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php

		} else {

			?>
			<p style="color: red;">Please Enable the cors headers via the filter OR register some header rules using the
				filter above.</p>
			<?php

		}

	}

}