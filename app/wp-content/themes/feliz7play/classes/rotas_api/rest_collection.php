<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/collection/(?P<id>\d+)', array(
	'methods' => 'GET',
    'callback' => 'get_rest_collection',
	));
});

function get_rest_collection($data) {

    $id = $data['id'];
    $page = $data->get_param('page');
    $per_page = $data->get_param('per_page');
    $order = $data->get_param('order');
    $orderby = $data->get_param('orderby');
    $meta_key = $data->get_param('meta_key');

    $args = array(  
      'post_type' => 'video',               
      'posts_per_page' => $per_page,               
      'paged' => $page,   
      'orderby' => array($orderby => 'ASC', 'date' => 'ASC'),  
      'order' => $order,
      'post_status' => 'publish',
      'tax_query' => array(
          array(
            'taxonomy' => 'collection',
            'field' => 'term_id',
            'terms' => $id,
            'include_children' => false
          )
        ),
        'meta_query' => array(
          'relation' => 'OR',
          array( 
              'key'=> $meta_key,
              'compare' => 'EXISTS'           
          ),
          array( 
              'key'=> $meta_key,
              'compare' => 'NOT EXISTS'           
          )
        ),
      );

    $posts = get_line_post($args);

    

	return new WP_REST_Response($posts, 200 );
}