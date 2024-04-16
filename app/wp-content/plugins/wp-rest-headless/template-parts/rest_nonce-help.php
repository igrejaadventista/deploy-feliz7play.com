<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */
?>

<div style="background:#0073aa; padding:20px;" >
	<a href="https://documenter.getpostman.com/view/1175138/RztkPpV4" target="_blank" style="color: #ffffff;">
		See example requests in plugin docs!!
	</a>
</div>

<p>This helps to add an extra layer of security for your public REST API endpoints by ensuring that all requests to the REST API must include a nonce.</p>

<p>JWT NOTE if all requests to the REST api provide the JWT auth header a nonce key is NOT required.</p>

<h2>Status:</h2>

<ul>
	<li>
		REST Nonce System :
		<?php
		$status = '<strong style="color:red;">Disabled</strong>';
		if( true === WP_Headless_Rest_Api_Nonce::$enable_rest_api_nonce ) {
			$status = '<strong style="color:green;">Enabled</strong>';
		}
		echo wp_kses_post( $status );
		?>
	</li>
	<li>
		REST Nonce Check :
		<?php
		$status = '<strong style="color:red;">Disabled</strong>';
		if( true === WP_Headless_Rest_Api_Nonce::$disable_nonce_check ) {
			$status = '<strong style="color:green;">Enabled</strong>';
		}
		echo wp_kses_post( $status );
		?>
	</li>
	<li>
		REST Nonce Generator Endpoint :
		<?php
		$status = '<strong style="color:red;">Disabled</strong>';
		if( true === WP_Headless_Rest_Api_Nonce::$enable_rest_api_generator ) {
			$status = '<strong style="color:green;">Enabled</strong>';
		}
		echo wp_kses_post( $status );
		?>
	</li>
</ul>

<hr>

<h4>Enable REST request nonce system</h4>
<pre>
add_filter( 'wp_headless_rest__enable_rest_nonce', '__return_true' );
</pre>

<hr>

<h4>Working with a Development and Production Environment?</h4>
<p>You can use this filter to remove the nonce check based on any logic you wish:</p>
<pre>
add_filter( 'wp_headless_rest__disable_rest_nonce_check', '__return_true' );
</pre>

<hr>

<h1>What type of App are you building?</h1>

<h3>Are you using the WordPress front end?</h3>
<p>If using the WordPress front end then all you have to do is generate the REST nonce and store it in a local object:</p>

<pre>
	add_action( 'wp_print_scripts', 'my_theme_theme_localize', 100 );
	function my_theme_theme_localize() {

		//Init vars
		$handle     = 'my-app-theme';
		$obj_name   = 'MyAppLocalVars';
		$data_array = array();

		$nonce = wp_headless_rest_generate_rest_nonce();

		$data_array['wp_api'] = array(
			'nonce'        => $nonce,
		);

		wp_localize_script( $handle, $obj_name, $data_array );
	}
</pre>

<p>Whenever you make a REST api request be sure to add the 'X-WP-NONCE' header with your nonce key.</p>

<hr>

<h3>You're building a mobile app not using WordPress front end?</h3>
<p>In all other cases you can generate the nonce via a REST api call. You can then store the nonce in local storage or a cookie and reuse it from there.</p>

<h4>1. Enable nonce generation via REST</h4>

<pre>
	add_filter( 'wp_headless_rest__enable_rest_nonce_generator_endpoint', '__return_true' );
</pre>

<h4>2. Generate a nonce</h4>

<p>To add a layer of security. Requests to generate a nonce require a public key the you set via this filter, Grab a key via https://api.wordpress.org/secret-key/1.1/salt/:</p>

<pre>
	add_filter( 'wp_headless_rest__rest_nonce_generator_key', function(){ return 'YOUR_PUBLIC_GENERATOR_KEY'; } );
</pre>

<p>Make your REST request to generate a nonce:</p>

<pre>
	POST: /wp-json/wp-headless/v1/rest-nonce/generate

	RESPONSE:
	{
		"nonce": "de2b183315"
	}
</pre>

<h4>3. Validate a nonce</h4>

<p>Nonce is only valid for 24 hours, this is the default time WordPress nonces. Expired nonce will trigger a 403 response from the REST api.</p>

<p>You can also check a nonce is valid with the validate request:</p>

<pre>
	POST: /wp-headless/v1/rest-nonce/validate
	HEADERS => X-WP-NONCE : NONCE_YOU_WANT_TO_VALIDATE

	SUCCESS RESPONSE:
	{
		"result": true,
		"data": {
			"status": 200
		}
	}

	FAIL RESPONSE:
	{
		"result": false,
		"data": {
			"status": 403
		}
	}

</pre>

<hr>