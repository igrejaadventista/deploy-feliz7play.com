<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/check_videos', array(
	'methods' => 'GET',
	'callback' => 'check_videos',
	));
});

function check_videos($data) {
  
    
    
	return new WP_REST_Response($itens_recent , 200 );
}