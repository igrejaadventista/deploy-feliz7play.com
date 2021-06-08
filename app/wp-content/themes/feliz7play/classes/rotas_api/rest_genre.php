<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/genre/(?P<id>\d+)', array(
	'methods' => 'GET',
    'callback' => 'get_rest_genre',
    
	));
});

function get_rest_genre($data) {

    $id = $data['id'];
    $page = $data->get_param('page');
    $per_page = $data->get_param('per_page');

    $items = get_genre_items($id);
    
    if($per_page > -1){ 
       
        $infos = pagination_array($items, $page, $per_page);
        $final = $infos['paged'];
        $resposta = new WP_REST_Response($final, 200);
        $resposta->header('X-WP-Total', count($items));
        $resposta->header('X-WP-TotalPages', $infos['totalPages']);

    }else{
       
        $resposta = new WP_REST_Response($items, 200);
    }
	
    return $resposta;

}

function get_genre_items($id){

    $term = get_term($id, 'genre');

    $items = array();

   $args = array(
       'taxonomy' => 'collection', 
       'meta_key' => 'collection_genre',
       'meta_value' => $id
    );

    $collection = get_line_collection($args);
   array_push($items, ...$collection['included']);


   $args = array(
       'post_type' => 'video',
     //'fields' => '',
       'genre' => $term->slug,
     //'exclude' => $collection['exclude'],
       'posts_per_page' => -1,
       'post_status' => 'publish',
       'meta_key' => 'post_video_type',
       'meta_value' => 'Single',
   
    );


   $post = get_line_post($args);

    array_push($items, ...$post);

    return $items;

}




