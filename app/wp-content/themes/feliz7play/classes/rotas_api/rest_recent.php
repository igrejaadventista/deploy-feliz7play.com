<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/recent', array(
	'methods' => 'GET',
	'callback' => 'get_recent',
	));
});

function get_recent($data) {

    $per_page = $data->get_param('per_page');

    $limited = is_null($per_page) ? 10 : $per_page;
   
    $args = array(
        'post_type'         => 'video',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
    );
  
    $itens_recent = get_line_post($args, $limited);
    
	return new WP_REST_Response($itens_recent , 200 );
}





