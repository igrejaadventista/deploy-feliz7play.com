<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */

$status = '<strong style="color:red;">Disabled</strong>';
if( true === WP_Headless_Rest_Gutenberg_Blocks::$enable_response ) {
	$status = '<strong style="color:green;">Enabled</strong>';
}
?>

<p>Gutenberg stores all block content and data attributes within comments inside the_content of posts.</p>

<p>What if you wanted to break out each block into a sperate content node within the JSON response like a tru restful api should?</p>

<p>Enable the REST gutenberg output with the filter below and you'll find a new 'wp_headless_gutenberg_data' node in the REST repsonse JSON with an array of each block and it's data in the order the user placed them.</p>

<h2>Status: <?php echo wp_kses_post( $status ); ?></h2>

<h4>Enable REST Gutenberg output with:</h4>
<pre>
add_filter( 'wp_headless_rest__enable_rest_gutenberg_blocks', '__return_true' );
</pre>

<hr/>

<h4>Register the post types which require the Gutenberg data</h4>

<p>By default only Post and Page are parsed for gutenberg data. Use the filter below to change this:</p>

<pre>
add_filter( 'wp_headless_rest__add_gutenberg_block_data_to_post_types', 'wp_headless_gutenberg_post_types' );
function wp_headless_gutenberg_post_types( $post_types_to_add_blocks_data ) {

	$post_types_to_add_blocks_data = array(
		'post',
		'page',
	);

	return $post_types_to_add_blocks_data;
}
</pre>

<hr/>

<h4>Now what?</h4>

<p>Look for the 'wp_headless_gutenberg_data' node in your REST response JSON object!</p>
