<?php 

class RestApi {
	public function __construct(){

		add_filter( 'wp_headless_rest__enable_rest_cleanup', '__return_true' );
		add_filter( 'wp_headless_rest__disable_front_end', '__return_false' );
		add_filter( 'wp_headless_rest__post_types_to_clean', [$this, 'wp_rest_headless_clean_post_types'] );
		add_filter( 'wp_headless_rest__rest_endpoints_to_remove', [$this, 'wp_rest_headless_disable_endpoints'] );
		//add_filter( 'wp_headless_rest__rest_object_remove_nodes', [$this, 'wp_rest_headless_clean_response_nodes'] );
	}
	
	function wp_rest_headless_clean_post_types( $post_types_to_clean ) {
		$post_types_to_clean = array(
			'post'
		);

		return $post_types_to_clean;
	}

	function wp_rest_headless_disable_endpoints( $endpoints_to_remove ) {
		$endpoints_to_remove = array(
			'/wp/v2/media',
			'/wp/v2/types',
			'/wp/v2/statuses',
			'/wp/v2/taxonomies',
			'/wp/v2/tags',
			'/wp/v2/users',
			'/wp/v2/comments',
			'/wp/v2/themes',
			'/wp/v2/blocks',
			'/wp/v2/block-renderer',
			'/oembed/',
			// '/wp/v2/pages',
			//JETPACK
			'jp_pay_product',
			'jp_pay_order',
			'/wp/v2/block-directory/search',
			'/wp/v2/plugins',
			'/wp/v2/settings',
			'/wp/v2/block-types',
		);

		return $endpoints_to_remove;
	}

	function wp_rest_headless_clean_response_nodes( $items_to_remove ) {
		$items_to_remove = array(
			'guid',
			'_links',
			'ping_status',
			'content',
			'excerpt'
		);

		return $items_to_remove;
	}
}

$RestApi = new RestApi();