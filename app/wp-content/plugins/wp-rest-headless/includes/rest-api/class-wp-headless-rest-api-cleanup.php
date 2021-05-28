<?php
/**
 * WP_Headless_Rest_Api_Cleanup
 *
 * Handle cleaning up the rest api response/endpoints
 *
 * GUTENBERG: Note this will NEVER run for Gutenberg requests to the rest api
 *
 * Configuration:
 *
 * Enable the rest nonce system:
 *  add_filter( 'wp_headless_rest__enable_rest_cleanup', '__return_true' );
 *
 * Set which rest endpoints to remove:
 *  add_filter( 'wp_headless_rest__rest_endpoints_to_remove',
 *  array(
 * 'media',
 * 'types',
 * 'statuses',
 * 'taxonomies',
 * 'tags',
 * 'users',
 * 'comments',
 * 'settings',
 * 'themes',
 * 'blocks',
 * 'oembed',
 * //JETPACK
 * 'jp_pay_product',
 * 'jp_pay_order',
 * )
 * );
 *
 * Set which post type rest responses to clean (defaults to post, page):
 *  add_filter( 'wp_headless_rest__post_types_to_clean', array('post', 'page')
 * );
 *
 * Set which response nodes to remove from the response object
 *  add_filter( 'wp_headless_rest__rest_object_remove_nodes', array('guid',
 * '_links', 'ping_status') );
 *
 * @author Ben Moody
 */

class WP_Headless_Rest_Api_Cleanup {

	public static $enable_rest_cleanup = false;

	public function __construct() {

		//Maybe enable rest nonce
		add_action(
			'init',
			array(
				$this,
				'maybe_enable_rest_cleanup',
			)
		);

	}

	/**
	 * clean_up_rest_response_object
	 *
	 * @CALLED BY FILTER "rest_prepare_{$post_type}"
	 *
	 * Helper to remove array of nodes from the rest api response object
	 *
	 * @param    object $response
	 * @param    object $post
	 * @param    object $request
	 *
	 * @return    object
	 * @access    public static
	 * @author    Ben Moody
	 */
	public static function clean_up_rest_response_object( $response, $post, $request ) {

		//vars
		$items_to_remove = array(
			'guid',
			'_links',
			'ping_status',
		);

		/**
		 * wp_headless_rest__rest_object_remove_nodes
		 *
		 * Allow devs to filter array of which nodes to remove from a posts rest response object
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Cleanup::clean_up_rest_response()
		 *
		 * @param array $items_to_remove Array of rest response object nodes to remove
		 */
		$items_to_remove = apply_filters( 'wp_headless_rest__rest_object_remove_nodes', $items_to_remove );

		if ( empty( $items_to_remove ) ) {
			return $response;
		}

		if ( ! is_array( $items_to_remove ) ) {
			return $response;
		}

		foreach ( $items_to_remove as $item_key ) {

			//Handle special case for _links array
			if ( '_links' === $item_key ) {

				foreach ( $response->get_links() as $link_key => $link_val ) {
					$response->remove_link( $link_key );
				}
			} else {

				if ( isset( $response->data[ $item_key ] ) ) {

					unset( $response->data[ $item_key ] );

				}
			}
		}

		return rest_ensure_response( $response );
	}

	/**
	 * maybe_enable_rest_cleanup
	 *
	 * @CALLED BY /ACTION 'init'
	 *
	 * Run process to detect if rest api cleaup is enabled
	 *
	 * @example enable with: add_filter(
	 *     'wp_headless_rest__enable_rest_cleanup',
	 *     '__return_true' );
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function maybe_enable_rest_cleanup() {

		//Disable for any gutenber requests
		if ( WP_Headless_Jwt_Rest_Api::is_gutenberg_request() ) {
			return;
		}

		/**
		 * wp_headless_rest__enable_rest_cleanup
		 *
		 * Allows devs to enable rest api cleanup, each stage of the process has it's own filters/configuration
		 *
		 * @example add_filter( 'wp_headless_rest__enable_rest_nonce', '__return_true' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Nonce::__construct()
		 *
		 * @param bool
		 */
		self::$enable_rest_cleanup = apply_filters( 'wp_headless_rest__enable_rest_cleanup', self::$enable_rest_cleanup );

		if ( true === self::$enable_rest_cleanup ) {

			//Remove endpoints
			add_filter(
				'rest_endpoints',
				array(
					$this,
					'remove_endpoints',
				),
				900,
				1
			);

			//Cleanup response object
			$this->cleanup_response_object();

		}

	}

	/**
	 * cleanup_response_object
	 *
	 * @CALLED BY $this->maybe_enable_rest_cleanup()
	 *
	 * Run the response object cleanup on post types indicated by filter
	 *     'wp_headless_rest__post_types_to_clean'
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function cleanup_response_object() {

		//vars
		$post_types_to_clean = array(
			'post',
			'page',
		);

		/**
		 * wp_headless_rest__post_types_to_clean
		 *
		 * Allow devs to filter array of post types whose response object you wish to cleanup
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Cleanup::cleanup_response_object()
		 *
		 * @param array $post_types_to_clean Array of post types whose response object you wish to cleanup
		 */
		$post_types_to_clean = apply_filters( 'wp_headless_rest__post_types_to_clean', $post_types_to_clean );

		if ( empty( $post_types_to_clean ) ) {
			return;
		}

		if ( ! is_array( $post_types_to_clean ) ) {
			return;
		}

		foreach ( $post_types_to_clean as $post_type ) {

			//Cleanup response object
			add_filter(
				"rest_prepare_{$post_type}",
				array(
					$this,
					'clean_up_rest_response_object',
				),
				900,
				3
			);

		}

	}

	/**
	 * remove_endpoints
	 *
	 * @CALLED BY FILTER 'rest_endpoints'
	 *
	 * Remove some default rest api endpoints
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function remove_endpoints( $endpoints ) {

		//vars
		$endpoints_to_remove = array(
			'/wp/v2/media',
			'/wp/v2/types',
			'/wp/v2/statuses',
			'/wp/v2/taxonomies',
			'/wp/v2/tags',
			'/wp/v2/users',
			'/wp/v2/comments',
			'/wp/v2/settings',
			'/wp/v2/themes',
			'/wp/v2/blocks',
			'/wp/v2/block-renderer',
			'/oembed/',
			//JETPACK
			'jp_pay_product',
			'jp_pay_order',
		);

		//Detect gutenberg requests
		if ( WP_Headless_Jwt_Rest_Api::is_gutenberg_request() ) {
			return $endpoints;
		}

		/**
		 * wp_headless_rest__rest_endpoints_to_remove
		 *
		 * Allow devs to filter endpoints they wish to remove
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Api_Cleanup::remove_endpoints()
		 *
		 * @param array $endpoints_to_remove array of endpoint titles you wish to remove from rest api
		 * @param array $endpoints array of active rest api endpoints
		 */
		$endpoints_to_remove = apply_filters( 'wp_headless_rest__rest_endpoints_to_remove', $endpoints_to_remove, $endpoints );

		if ( empty( $endpoints_to_remove ) ) {
			return $endpoints;
		}

		foreach ( $endpoints_to_remove as $endpoint ) {

			foreach ( $endpoints as $maybe_remove_endpoint_base => $endpoint_config ) {

				//Should we remove this endpoint?
				if ( strpos( $maybe_remove_endpoint_base, $endpoint ) !== false ) {
					unset( $endpoints[ $maybe_remove_endpoint_base ] );
				}
			}
		}

		return $endpoints;
	}

}

new WP_Headless_Rest_Api_Cleanup();
