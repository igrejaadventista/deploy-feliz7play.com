<?php
  
  add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/genre/(?P<id>\d+)', array(
	'methods' => 'GET',
    'callback' => 'get_rest_genre'
	));
});

function get_rest_genre($data) {

    $id = $data['id'];
    $page = $data->get_param('page');
    $per_page = $data->get_param('per_page');
    $id_exclude = $data->get_param('id_exclude');
    $type_exclude = $data->get_param('type_exclude');

    $args = array(
        'post_type'         => 'video',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
        'exclude'           => $id_exclude,
        'tax_query' => array(
            array(
                'taxonomy' => 'genre',
                'field'    => 'term_id',
                'terms'    => $id,
            ),
        ),
    );
    
    $items = get_line_post_genre($args);
       
    $infos = pagination_array($items, $page, $per_page);
    $final = $infos['paged'];
    $resposta = new WP_REST_Response($final, 200);
    $resposta->header('X-WP-Total', count($items));
    $resposta->header('X-WP-TotalPages', $infos['totalPages']);

    return $resposta;

}





