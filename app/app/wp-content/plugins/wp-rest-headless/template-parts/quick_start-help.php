<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */
?>

<h2>Impatient to get started eh?</h2>

<p>This framework has allot of features so it's recommened that you read the next tabs to familiarise yourself as to what the framework can do.</p>

<p>If you wanted to get started with a base configuration, there are two examples below based on two different use cases for a headless CMS.</p>

<p>Copy those code chunks and be sure to read the comments as some require your input to configure!</p>

<hr>

<h4>Semi Headless CMS where you are using the WordPress frontend:</h4>
<pre>
# NONCE - see the REST Nonce docs for examples on how to generate a fresh nonce key
add_filter( 'wp_headless_rest__enable_rest_nonce', '__return_true' );

//Enable this on your DEV environment to allow rest request without the nonce
//add_filter( 'wp_headless_rest__disable_rest_nonce_check', '__return_true' );

# REST CLEANUP - see the REST Cleanup docs in plugin settings as you may want to configure this beyond default
add_filter( 'wp_headless_rest__enable_rest_cleanup', '__return_true' );

# Gutenberg - see REST Gutenberg docs as you will need to register the post types you want to use this with
add_filter( 'wp_headless_rest__enable_rest_gutenberg_blocks', '__return_true' );

/**
 * Checkout the JWT Authenication docs as well as the request example docs:
 *
 * @link https://documenter.getpostman.com/view/1175138/RztkPpV4#fc567f81-ff31-442e-a236-fa247bd1fd6c
 *
 */
add_filter( 'wp_headless_rest__enable_jwt', '__return_true' );

# CORS Security Headers - in most cases you can use the defaults but see the docs if you want to customise
//add_filter( 'wp_headless_rest__disable_cors', '__return_true' );
</pre>

<hr/>

<h4>True Headless CMS where you building a Single Page App or Mobile App away from WordPress frontend:</h4>
<pre>
# NONCE - see the REST Nonce docs for examples on how to generate a fresh nonce key
add_filter( 'wp_headless_rest__enable_rest_nonce', '__return_true' );

/**
 * You will need to generate and validate your nonce via the REST API.
 * Once enabled, you will also need to set a public key which will be passed to the generate nonce request
 *
 * NOTE if all requests to the REST api provide the JWT auth header a nonce key is NOT required
 *
 * See the REST Nonce in the plugin admin docs as well as these request examples
 * @link https://documenter.getpostman.com/view/1175138/RztkPpV4#fc567f81-ff31-442e-a236-fa247bd1fd6c
 *
 */
add_filter( 'wp_headless_rest__enable_rest_nonce_generator_endpoint', '__return_true' );

//NOTE if all requests to the REST api provide the JWT auth header a nonce key is NOT required
add_filter( 'wp_headless_rest__rest_nonce_generator_key', function(){ return ''; } );

//Enable this on your DEV environment to allow rest request without the nonce
//add_filter( 'wp_headless_rest__disable_rest_nonce_check', '__return_true' );

# REST CLEANUP - see the REST Cleanup docs in plugin settings as you may want to configure this beyond default
add_filter( 'wp_headless_rest__enable_rest_cleanup', '__return_true' );

# Gutenberg - see REST Gutenberg docs as you will need to register the post types you want to use this with
add_filter( 'wp_headless_rest__enable_rest_gutenberg_blocks', '__return_true' );

/**
 * Checkout the JWT Authenication docs as well as the request example docs:
 *
 * @link https://documenter.getpostman.com/view/1175138/RztkPpV4#fc567f81-ff31-442e-a236-fa247bd1fd6c
 *
 */
add_filter( 'wp_headless_rest__enable_jwt', '__return_true' );

# CORS Security Headers - in most cases you can use the defaults but see the docs if you want to customise
//add_filter( 'wp_headless_rest__disable_cors', '__return_true' );
</pre>

<hr/>