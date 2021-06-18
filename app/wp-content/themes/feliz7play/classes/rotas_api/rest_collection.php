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

    $term = get_term($data['id'], 'collection');

    $args = array(  'post_type' => 'video', 
                    'posts_per_page' => $per_page, 
                    'paged' => $page,
                    'orderby' => $orderby,
                    'order' => $order,
                    'post_status' => 'publish',
                    'tax_query' => array(
                        array(
                          'taxonomy' => 'collection',
                          'field' => 'slug',
                          'terms' => $term->slug,
                          'include_children' => false
                        )
                      )
                    );
    $posts = get_line_post($args);

	return new WP_REST_Response($posts, 200 );
}