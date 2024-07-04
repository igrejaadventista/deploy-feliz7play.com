<?php 

class VideoCPT {
	public function __construct(){

		add_action( 'init', [$this, 'create_Video_cpt'], 0 );  
	}
	
	function create_video_cpt() {

		$labels = array(
			'name'                  => _x( 'Videos', 'Post Type General Name', 'f7p' ),
			'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'f7p' ),
			'menu_name'             => __( 'Video', 'f7p' ),
			'name_admin_bar'        => __( 'Video', 'f7p' ),
			'archives'              => __( 'Item Archives', 'f7p' ),
			'attributes'            => __( 'Item Attributes', 'f7p' ),
			'parent_item_colon'     => __( 'Parent Item:', 'f7p' ),
			'all_items'             => __( 'All Items', 'f7p' ),
			'add_new_item'          => __( 'Add New Item', 'f7p' ),
			'add_new'               => __( 'Add New', 'f7p' ),
			'new_item'              => __( 'New Item', 'f7p' ),
			'edit_item'             => __( 'Edit Item', 'f7p' ),
			'update_item'           => __( 'Update Item', 'f7p' ),
			'view_item'             => __( 'View Item', 'f7p' ),
			'view_items'            => __( 'View Items', 'f7p' ),
			'search_items'          => __( 'Search Item', 'f7p' ),
			'not_found'             => __( 'Not found', 'f7p' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'f7p' ),
			'featured_image'        => __( 'Featured Image', 'f7p' ),
			'set_featured_image'    => __( 'Set featured image', 'f7p' ),
			'remove_featured_image' => __( 'Remove featured image', 'f7p' ),
			'use_featured_image'    => __( 'Use as featured image', 'f7p' ),
			'insert_into_item'      => __( 'Insert into item', 'f7p' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'f7p' ),
			'items_list'            => __( 'Items list', 'f7p' ),
			'items_list_navigation' => __( 'Items list navigation', 'f7p' ),
			'filter_items_list'     => __( 'Filter items list', 'f7p' ),
		);
		$args = array(
			'label'                 => __( 'Video', 'f7p' ),
			'description'           => __( 'Post Type Description', 'f7p' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'thumbnail' ),
			'taxonomies'            => array( 'genre', 'collection', 'category' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-video-alt3',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'rewrite'               => false,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rest_base'             => 'video',
		);
		register_post_type( 'video', $args );
	}

}

$VideoCPT = new VideoCPT();