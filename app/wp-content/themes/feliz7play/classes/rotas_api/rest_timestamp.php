<?php

add_action('publish_post', 'save_post_timestamp', 10, 2);
add_action('save_post', 'save_post_timestamp', 10, 2);
add_action('transition_post_status', 'save_post_timestamp', 10, 2);
add_action('created_term', 'save_post_timestamp', 10, 2);
add_action('edited_terms', 'save_post_timestamp', 10, 3);


function save_post_timestamp( $post_id, $post ){
    
    update_option( "post_timestamp", time(), true );
}

add_action( 'rest_api_init', 'rest_post_timestamp' );
function rest_post_timestamp() {
    register_rest_route( 'wp/v3', '/timestamp', array(
        'methods' => 'GET',
        'callback' => 'get_timestamp',
    ) );
}

function get_timestamp(){
    //echox('teste');
    return new WP_REST_Response( get_option("post_timestamp"), 200 );
}

function clear_advert_main_transient() {
	$screen = get_current_screen();
	if (strpos($screen->id, "acf-options-theme-options") == true) {
        update_option( "post_timestamp", $timestamp = strtotime(date("D M d, Y G:i")), true );
	}
}
add_action('acf/save_post', 'clear_advert_main_transient', 20);

