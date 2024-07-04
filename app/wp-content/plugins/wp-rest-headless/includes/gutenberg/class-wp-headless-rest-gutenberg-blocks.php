<?php
/**
 * WP_Headless_Rest_Gutenberg_Blocks
 *
 * Parses post content of select post types and adds a node to the rest api
 * response object with all block data
 *
 * Configuration:
 *
 * Enable the gutenberg block data in rest response object (under
 * 'wp_headless_gutenberg_data' node): add_filter(
 * 'wp_headless_rest__enable_rest_gutenberg_blocks', '__return_true' );
 *
 * @author Ben Moody
 */

class WP_Headless_Rest_Gutenberg_Blocks {

	public static $enable_response = false;

	public function __construct() {

		//Maybe enable gutenberg response object
		add_action(
			'init',
			array(
				$this,
				'maybe_enable_gutenberg_response',
			)
		);

	}

	/**
	 * maybe_enable_gutenberg_response
	 *
	 * @CALLED BY /ACTION 'init'
	 *
	 * Run process to detect if gutenberg reponse is enabled
	 *
	 * @example enable with: add_filter(
	 *     'wp_headless_rest__enable_rest_cleanup',
	 *     '__return_true' );
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function maybe_enable_gutenberg_response() {

		//Disable for any gutenberg requests
		if ( WP_Headless_Jwt_Rest_Api::is_gutenberg_request() ) {
			return;
		}

		/**
		 * wp_headless_rest__enable_rest_gutenberg_blocks
		 *
		 * Allows devs to enable the rendering of the gutenberg blocks node in rest response
		 *
		 * @example add_filter( 'wp_headless_rest__enable_rest_gutenberg_blocks', '__return_true' );
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Gutenberg_Blocks::maybe_enable_gutenberg_response()
		 *
		 * @param bool
		 */
		self::$enable_response = apply_filters( 'wp_headless_rest__enable_rest_gutenberg_blocks', self::$enable_response );

		if ( true === self::$enable_response ) {

			//Add gutenberg blocks to post response object
			$this->add_gutenberg_to_post_response();

		}

	}

	/**
	 * add_gutenberg_to_post_response
	 *
	 * @CALLED BY FILTER/ACTION ''
	 *
	 * Add rest_prepare_{$post_type} passing the gutenberg block data parser
	 *     for each post type registered to render block data via filter
	 *     'wp_headless_rest__add_gutenberg_block_data_to_post_types'
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function add_gutenberg_to_post_response() {

		//vars
		$post_types_to_add_blocks_data = array(
			'post',
			'page',
		);

		/**
		 * wp_headless_rest__add_gutenberg_block_data_to_post_types
		 *
		 * Allow devs to filter array of post types whose response object you wish to add gutenberg block data to
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Headless_Rest_Gutenberg_Blocks::add_gutenberg_to_post_response()
		 *
		 * @param array $post_types_to_add_blocks_data
		 */
		$post_types_to_add_blocks_data = apply_filters( 'wp_headless_rest__add_gutenberg_block_data_to_post_types', $post_types_to_add_blocks_data );

		if ( empty( $post_types_to_add_blocks_data ) ) {
			return;
		}

		if ( ! is_array( $post_types_to_add_blocks_data ) ) {
			return;
		}

		foreach ( $post_types_to_add_blocks_data as $post_type ) {

			//Cleanup response object
			add_filter(
				"rest_prepare_{$post_type}",
				array(
					$this,
					'parse_and_add_block_data_node',
				),
				900,
				3
			);

		}

	}

	/**
	 * parse_and_add_block_data_node
	 *
	 * @CALLED BY FILTER "rest_prepare_{$post_type}"
	 *
	 * Helper to parse and add gutenberg block data to rest response object
	 *
	 * If valid gutenberg data is found in post content you will find it in the
	 *     'wp_headless_gutenberg_data' node of the response object
	 *
	 * @param    object $response
	 * @param    object $post
	 * @param    object $request
	 *
	 * @return    object
	 * @access    public
	 * @author    Ben Moody
	 */
	public function parse_and_add_block_data_node( $response, $post, $request ) {

		//vars
		$block_data    = null;
		$response_data = array();

		if ( ! isset( $post->post_content ) ) {
			return rest_ensure_response( $response );
		}

		//Cache response data
		$response_data = $response->get_data();

		//Detect gutenberg blocks in post content
		$block_data = parse_blocks( $post->post_content );

		if ( empty( $block_data ) || ! is_array( $block_data ) ) {
			return rest_ensure_response( $response );
		}

		//Loop blocks and pass to block json renderer
		foreach ( $block_data as $block ) {

			if ( ! isset( $block['blockName'] ) ) {
				continue;
			}

			$response_data['wp_headless_gutenberg_data'][ $block['blockName'] ] = $block;

		}

		$response->set_data( $response_data );

		return rest_ensure_response( $response );
	}

}

new WP_Headless_Rest_Gutenberg_Blocks();
