<?php

class CustomTaxonomies {
	public function __construct(){
		add_action( 'init', [$this, 'unregister_taxonomy'] );
		add_action( 'init', [$this, 'f7p_genre_tax'] );
		add_action( 'init', [$this, 'f7p_collection_tax'] );
		add_action( 'init', [$this, 'f7p_category_tax'] );
		add_action( 'init', [$this, 'f7p_language_audio_tax'] );
		add_action( 'init', [$this, 'f7p_language_subtitle_tax'] );
	}

	function unregister_taxonomy() {
		global $wp_taxonomies;
		$taxonomy = array('category', 'post_tag');
		foreach ($taxonomy as &$value) {
			if ( taxonomy_exists($value) ){
				unset( $wp_taxonomies[$value] );
			}
		}
	}

	function f7p_genre_tax() {

		$labels = array(
			'name'                       => _x( 'Genres', 'Taxonomy General Name', 'f7p' ),
			'singular_name'              => _x( 'Genre', 'Taxonomy Singular Name', 'f7p' ),
			'menu_name'                  => __( 'Genre', 'f7p' ),
			'all_items'                  => __( 'All Items', 'f7p' ),
			'parent_item'                => __( 'Parent Item', 'f7p' ),
			'parent_item_colon'          => __( 'Parent Item:', 'f7p' ),
			'new_item_name'              => __( 'New Item Name', 'f7p' ),
			'add_new_item'               => __( 'Add New Item', 'f7p' ),
			'edit_item'                  => __( 'Edit Item', 'f7p' ),
			'update_item'                => __( 'Update Item', 'f7p' ),
			'view_item'                  => __( 'View Item', 'f7p' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'f7p' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'f7p' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'f7p' ),
			'popular_items'              => __( 'Popular Items', 'f7p' ),
			'search_items'               => __( 'Search Items', 'f7p' ),
			'not_found'                  => __( 'Not Found', 'f7p' ),
			'no_terms'                   => __( 'No items', 'f7p' ),
			'items_list'                 => __( 'Items list', 'f7p' ),
			'items_list_navigation'      => __( 'Items list navigation', 'f7p' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'genre',
		);
		register_taxonomy( 'genre', array( 'video' ), $args );
	}

	function f7p_collection_tax() {

		$labels = array(
			'name'                       => _x( 'Collections', 'Taxonomy General Name', 'f7p' ),
			'singular_name'              => _x( 'Collection', 'Taxonomy Singular Name', 'f7p' ),
			'menu_name'                  => __( 'Collection', 'f7p' ),
			'all_items'                  => __( 'All Items', 'f7p' ),
			'parent_item'                => __( 'Parent Item', 'f7p' ),
			'parent_item_colon'          => __( 'Parent Item:', 'f7p' ),
			'new_item_name'              => __( 'New Item Name', 'f7p' ),
			'add_new_item'               => __( 'Add New Item', 'f7p' ),
			'edit_item'                  => __( 'Edit Item', 'f7p' ),
			'update_item'                => __( 'Update Item', 'f7p' ),
			'view_item'                  => __( 'View Item', 'f7p' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'f7p' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'f7p' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'f7p' ),
			'popular_items'              => __( 'Popular Items', 'f7p' ),
			'search_items'               => __( 'Search Items', 'f7p' ),
			'not_found'                  => __( 'Not Found', 'f7p' ),
			'no_terms'                   => __( 'No items', 'f7p' ),
			'items_list'                 => __( 'Items list', 'f7p' ),
			'items_list_navigation'      => __( 'Items list navigation', 'f7p' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'collection',
		);
		register_taxonomy( 'collection', array( 'video' ), $args );
	}

	function f7p_category_tax() {

		$labels = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'f7p' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'f7p' ),
			'menu_name'                  => __( 'Category', 'f7p' ),
			'all_items'                  => __( 'All Items', 'f7p' ),
			'parent_item'                => __( 'Parent Item', 'f7p' ),
			'parent_item_colon'          => __( 'Parent Item:', 'f7p' ),
			'new_item_name'              => __( 'New Item Name', 'f7p' ),
			'add_new_item'               => __( 'Add New Item', 'f7p' ),
			'edit_item'                  => __( 'Edit Item', 'f7p' ),
			'update_item'                => __( 'Update Item', 'f7p' ),
			'view_item'                  => __( 'View Item', 'f7p' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'f7p' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'f7p' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'f7p' ),
			'popular_items'              => __( 'Popular Items', 'f7p' ),
			'search_items'               => __( 'Search Items', 'f7p' ),
			'not_found'                  => __( 'Not Found', 'f7p' ),
			'no_terms'                   => __( 'No items', 'f7p' ),
			'items_list'                 => __( 'Items list', 'f7p' ),
			'items_list_navigation'      => __( 'Items list navigation', 'f7p' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'category',
		);
		register_taxonomy( 'category', array( 'video' ), $args );
	}

	function f7p_language_audio_tax() {
		$labels = array(
			'name'                       => _x( 'Languages (Audio)', 'Taxonomy General Name', 'f7p' ),
			'singular_name'              => _x( 'Language (Audio)', 'Taxonomy Singular Name', 'f7p' ),
			'menu_name'                  => __( 'Language (Audio)', 'f7p' ),
			'all_items'                  => __( 'All Items', 'f7p' ),
			'parent_item'                => __( 'Parent Item', 'f7p' ),
			'parent_item_colon'          => __( 'Parent Item:', 'f7p' ),
			'new_item_name'              => __( 'New Item Name', 'f7p' ),
			'add_new_item'               => __( 'Add New Item', 'f7p' ),
			'edit_item'                  => __( 'Edit Item', 'f7p' ),
			'update_item'                => __( 'Update Item', 'f7p' ),
			'view_item'                  => __( 'View Item', 'f7p' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'f7p' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'f7p' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'f7p' ),
			'popular_items'              => __( 'Popular Items', 'f7p' ),
			'search_items'               => __( 'Search Items', 'f7p' ),
			'not_found'                  => __( 'Not Found', 'f7p' ),
			'no_terms'                   => __( 'No items', 'f7p' ),
			'items_list'                 => __( 'Items list', 'f7p' ),
			'items_list_navigation'      => __( 'Items list navigation', 'f7p' ),
		);
		$args = array(
			'labels'                     => $labels,
			// 'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'language_audio',
		);
		register_taxonomy( 'language_audio', array( 'video' ), $args );
	}

	function f7p_language_subtitle_tax() {
		$labels = array(
			'name'                       => _x( 'Languages (Subtitle)', 'Taxonomy General Name', 'f7p' ),
			'singular_name'              => _x( 'Language (Subtitle)', 'Taxonomy Singular Name', 'f7p' ),
			'menu_name'                  => __( 'Language (Subtitle)', 'f7p' ),
			'all_items'                  => __( 'All Items', 'f7p' ),
			'parent_item'                => __( 'Parent Item', 'f7p' ),
			'parent_item_colon'          => __( 'Parent Item:', 'f7p' ),
			'new_item_name'              => __( 'New Item Name', 'f7p' ),
			'add_new_item'               => __( 'Add New Item', 'f7p' ),
			'edit_item'                  => __( 'Edit Item', 'f7p' ),
			'update_item'                => __( 'Update Item', 'f7p' ),
			'view_item'                  => __( 'View Item', 'f7p' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'f7p' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'f7p' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'f7p' ),
			'popular_items'              => __( 'Popular Items', 'f7p' ),
			'search_items'               => __( 'Search Items', 'f7p' ),
			'not_found'                  => __( 'Not Found', 'f7p' ),
			'no_terms'                   => __( 'No items', 'f7p' ),
			'items_list'                 => __( 'Items list', 'f7p' ),
			'items_list_navigation'      => __( 'Items list navigation', 'f7p' ),
		);
		$args = array(
			'labels'                     => $labels,
			// 'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'language_subtitle',
		);
		register_taxonomy( 'language_subtitle', array( 'video' ), $args );
	}

}
$PaImageThumbs = new CustomTaxonomies();

