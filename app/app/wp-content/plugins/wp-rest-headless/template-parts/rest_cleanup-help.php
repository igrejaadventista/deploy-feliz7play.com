<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */

$status = '<strong style="color:red;">Disabled</strong>';
if( true === WP_Headless_Rest_Api_Cleanup::$enable_rest_cleanup ) {
	$status = '<strong style="color:green;">Enabled</strong>';
}
?>

<p>The deafult WordPress REST api provides allot of endpoints and response data nodes, many of which your project will not need.</p>

<p>Why not clean it up a little?</p>

<h2>Status: <?php echo wp_kses_post( $status ); ?></h2>

<h4>Enable REST Cleanup with</h4>
<pre>
add_filter( 'wp_headless_rest__enable_rest_cleanup', '__return_true' );
</pre>

<hr/>

<h4>Set which endpoints to disable:</h4>

<p>You can disable REST api enpoints that you are not using and hide them from public access:</p>

<pre>
add_filter( 'wp_headless_rest__rest_endpoints_to_remove', 'wp_rest_headless_disable_endpoints' );
function wp_rest_headless_disable_endpoints( $endpoints_to_remove ) {

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


	return $endpoints_to_remove;
}
</pre>

<hr/>

<h3>Response Object Cleanup:</h3>

<h4>Select which post types to clean:</h4>

<pre>
add_filter( 'wp_headless_rest__post_types_to_clean', 'wp_rest_headless_clean_post_types' );
function wp_rest_headless_clean_post_types( $post_types_to_clean ) {

	$post_types_to_clean = array(
		'post',
		'page',
	);

	return $post_types_to_clean;
}
</pre>

<h4>Select what to remove from the response object:</h4>

<pre>
add_filter( 'wp_headless_rest__rest_object_remove_nodes', 'wp_rest_headless_clean_response_nodes' );
function wp_rest_headless_clean_response_nodes( $items_to_remove ) {

	$items_to_remove = array(
		'guid',
		'_links',
		'ping_status',
	);

	return $items_to_remove;
}
</pre>