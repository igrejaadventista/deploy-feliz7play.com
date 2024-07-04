<?php
/**
 * WP_Headless_Settings
 *
 * Handles all logic for generating the plugins settings page, registered under
 * options
 *
 * @author Ben Moody
 */

class WP_Headless_Settings extends WP_Headless_Core {

	protected $page_title;
	protected $menu_slug;
	protected $menu_title;
	protected $option_group;
	protected $settings_section_id;

	public function __construct() {

		//Setup globals for settings page
		$this->set_globals();

		// create custom plugin settings menu
		add_action( 'admin_menu', array( $this, 'create_settings_menu' ), 5 );

		//Register our plugin settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	/**
	 * set_globals
	 *
	 * @CALLED BY __construct()
	 *
	 * Setup any class globals for use in plugin code
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function set_globals() {

		//Set settings menu slug
		$this->menu_slug = self::$plugin_prefix . 'settings';

		//Set settings page title
		$this->page_title = esc_html_x( 'WP REST Headless', 'page title', WP_REST_HEADLESS_TEXT_DOMAIN );

		//Set settings menu title
		$this->menu_title = esc_html_x( 'WP REST Headless', 'admin menu title', WP_REST_HEADLESS_TEXT_DOMAIN );

		//Set option group slug
		$this->option_group = 'wp-rest-headless-plugin-settings-group';

		//Main settings section id
		$this->settings_section_id = self::$plugin_prefix . 'settings';
	}

	/**
	 * get_option
	 *
	 * Static helper method, used in front end template to get plugin options,
	 * allows for a handy custom filter for option data
	 * "wp_rest_headless_filter_option__{$option_name}"
	 *
	 * @param string $option_name
	 * @param string $default
	 *
	 * @return mixed $option
	 * @access public static
	 * @author Ben Moody
	 */
	public static function get_option( $option_name = null, $default = null ) {

		//vars
		$option = null;

		if ( empty( $option_name ) ) {
			return false;
		}

		//Get option from WP
		$option = get_option( $option_name, $default );

		/**
		 * Filter plugin options
		 *
		 * @since 1.0.0
		 *
		 * @param $option
		 */
		$option = apply_filters( "wp_rest_headless_filter_option__{$option_name}", $option );

		return $option;
	}

	/**
	 * create_settings_menu
	 *
	 * @CALLED BY /ACTION 'admin_menu'
	 *
	 * Handles registering the options page with WP
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function create_settings_menu() {

		//add a new menu item. This is a top level menu item i.e., this menu item can have sub menus
		add_menu_page(
			$this->page_title,
			$this->menu_title,
			'manage_options', //Required. The required capability of users to access this menu item.
			$this->menu_slug, //Required. A unique identifier to identify this menu item.
			array(
				$this,
				'render_main_settings_page',
			), //Optional. This callback outputs the content of the page associated with this menu item.
			'dashicons-cloud', //Optional. The URL to the menu item icon.
			100 //Optional. Position of the menu item in the menu.
		);

		return;
	}

	/**
	 * register_settings
	 *
	 * @CALLED BY /ACTION 'admin_init'
	 *
	 * Register the plugin option settings with WP
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function register_settings() {

		//Add admin notices for settings errors
		add_action(
			'admin_notices',
			function () {

				settings_errors();
			}
		);

		//Register plugin settings section
		add_settings_section(
			$this->settings_section_id,
			esc_html_x( 'Help:', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
			array( $this, 'render_tab_section_help' ),
			$this->menu_slug
		);

		//Create endpoints section and register settings
		$this->create_tab__request_headers();

		return;
	}

	/**
	 * get_active_settings_page_tab
	 *
	 * Helper to return which settings page tab is active
	 *
	 * @return string $active_tab
	 * @access public static
	 * @author Ben Moody
	 */
	public static function get_active_settings_page_tab() {

		//then we get the active tab.
		$active_tab = 'request-headers';

		if ( isset( $_GET['tab'] ) ) {

			$active_tab = sanitize_key( $_GET['tab'] );

		}

		return $active_tab;
	}

	/**
	 * create_tab__endpoints
	 *
	 * @CALLED BY $this->register_settings()
	 *
	 * Create instance of class responsible for Endpoints tab
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function create_tab__request_headers() {

		//Detect current options page tab
		$active_tab = WP_Headless_Settings::get_active_settings_page_tab();

		//Headers options tab
		if ( 'request-headers' === $active_tab ) {

			$post_types = new WP_Headless_Settings_Request_Headers();

			$post_types->create_fields();

		}

		//JWT options tab
		if ( 'rest-jwt' === $active_tab ) {

			$post_types = new WP_Headless_Settings_Rest_Jwt();

			$post_types->create_fields();

		}

		//REST nonce options tab
		if ( 'rest-nonce' === $active_tab ) {

			$post_types = new WP_Headless_Settings_Rest_Nonce();

			$post_types->create_fields();

		}

		//REST cleanup options tab
		if ( 'rest-cleanup' === $active_tab ) {

			$post_types = new WP_Headless_Settings_Rest_Cleanup();

			$post_types->create_fields();

		}

	}

	/**
	 * render_tab_section_help
	 *
	 * @CALLED BY add_settings_section() callback
	 *
	 * Render the help content for the active tab
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function render_tab_section_help() {

		//vars
		$template_path = null;

		//Detect current options page tab
		$active_tab = WP_Headless_Settings::get_active_settings_page_tab();

		if ( 'request-headers' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'request_headers', 'help' );

			wp_rest_headless_include_file( $template_path );

		} elseif ( 'rest-jwt' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'rest_jwt', 'help' );

			wp_rest_headless_include_file( $template_path );

		} elseif ( 'rest-nonce' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'rest_nonce', 'help' );

			wp_rest_headless_include_file( $template_path );

		} elseif ( 'rest-cleanup' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'rest_cleanup', 'help' );

			wp_rest_headless_include_file( $template_path );

		} elseif ( 'rest-gutenberg' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'rest_gutenberg', 'help' );

			wp_rest_headless_include_file( $template_path );

		} elseif ( 'disable-frontend' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'disable_frontend', 'help' );

			wp_rest_headless_include_file( $template_path );

		} elseif ( 'quick-start' === $active_tab ) {

			$template_path = wp_rest_headless_get_template_path( 'quick_start', 'help' );

			wp_rest_headless_include_file( $template_path );

		}

		return;
	}

	/**
	 * render_main_settings_page
	 *
	 * @CALLED callback for add_options_page()
	 *
	 * Finds the settings form template file to render the options page form.
	 *
	 * NOTE as with all plugin template files you can override them in your
	 *     theme see wp_rest_headless_get_template_path function description
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function render_main_settings_page() {

		//vars
		$template_path = null;
		$page_title    = $this->page_title;
		$option_group  = $this->option_group;
		$menu_slug     = $this->menu_slug;

		$template_path = wp_rest_headless_get_template_path( 'settings_main', 'form' );

		//Check if a valid path for include
		if ( validate_file( $template_path ) > 0 ) {

			//Failed path validation
			return new WP_Error(
				'render_main_settings_page',
				'File require path failed path validation',
				$template_path
			);

		}

		require_once( $template_path );

		return;
	}

}

new WP_Headless_Settings();
