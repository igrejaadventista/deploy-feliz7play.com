<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */

$status = '<strong style="color:red;">Disabled</strong>';
if( false === WP_Headless_Rest_Api_Cors::$disable ) {
	$status = '<strong style="color:green;">Enabled</strong>';
}
?>

<h2>Status: <?php echo wp_kses_post( $status ); ?></h2>

<h4>Disable plugin header generation with</h4>
<pre>
add_filter( 'wp_headless_rest__disable_cors', '__return_true' );
</pre>

<hr/>

<h4>Filter Header Rules with</h4>
<p><strong>WARNING</strong> you will require the correct Access-Control-Allow-Headers for JWT auth to work: <br>Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Authorization</p>
<pre>
add_filter( 'wp_headless_rest__cors_rules', 'wp_rest_headless_header_rules' );
function wp_rest_headless_header_rules( $rules ) {

	$rules = array(
		'Access-Control-Allow-Origin'      => $origin,
		'Access-Control-Allow-Methods'     => 'GET, POST',
		'Access-Control-Allow-Credentials' => 'true',
		'Access-Control-Allow-Headers'     => 'Access-Control-Allow-Headers, Content-Type, Authorization, origin, x-wp-nonce, x-wp-nonce-generator',
		'Access-Control-Expose-Headers'    => array( 'Link', false ), //Use array if replace param is required
	);

	return $rules;
}
</pre>

<hr/>
