<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */

$status = '<strong style="color:red;">Disabled</strong>';
if( true === WP_Headless_Rest_Disable_Frontend::$disable_front_end ) {
	$status = '<strong style="color:green;">Enabled</strong>';
}
?>

<p>A true Headless CMS should be...headless. I guess...</p>

<p>That's not always the case though. The frontend is enabled by default just in case you want to create a single page app but still use a WordPress theme.</p>

<p>Enable this module to turn off the WordPress front end using the filter below.</p>

<h2>Status: <?php echo wp_kses_post( $status ); ?></h2>

<h4>Enable module to turn off the WP front end:</h4>
<p>All requests to any front end page (except the wp admin/login) will return 404.</p>
<pre>
add_filter( 'wp_headless_rest__disable_front_end', '__return_false' );
</pre>

<hr/>
