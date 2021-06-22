<?php

class ThemeHelper {

	public function __construct(){
		add_action( 'acf/init', [$this, 'addThemeOptions'], 10 ); 
		add_action( 'admin_init', [$this, 'remove_textarea'] );
		add_filter( 'register_post_type_args', [$this, 'remove_default_post_type'], 0, 2);
		add_action( 'after_setup_theme', [$this, 'createThumbs' ]);
	}

	function addThemeOptions(){
		if( function_exists('acf_add_options_page') ) {  
			acf_add_options_sub_page(array(
				'page_title' 	=> 'Home Options',
				'menu_title'	=> 'Home Options',
				'parent_slug'	=> 'f7p-general-settings',
				'capability' 	=> 'add_users',
			));
		}
	}

	function remove_textarea() {
		remove_post_type_support( 'post', 'editor' );
	}

	function remove_default_post_type($args, $postType) {
		if ($postType === 'post') {
			$args['public']                = false;
			$args['show_ui']               = false;
			$args['show_in_menu']          = false;
			$args['show_in_admin_bar']     = false;
			$args['show_in_nav_menus']     = false;
			$args['can_export']            = false;
			$args['has_archive']           = false;
			$args['exclude_from_search']   = true;
			$args['publicly_queryable']    = false;
			$args['show_in_rest']          = false;
		}
	
		return $args;
	}

	function createThumbs(){
		//add_image_size( 'lider-thumb', 250, 250, true );
	}

}

$ThemeHelper = new ThemeHelper();