<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/suggestion', array(
	'methods' => 'GET',
	'callback' => 'get_page_option_suggestion',
	));
});

$lines = array();
$total = array();

function get_page_option_suggestion($data) {
	
    global $total;
    
    suggestion1();
    suggestion2();
           
    
	return new WP_REST_Response($total , 200 );
}


function suggestion2(){

    global $lines;
    global $total;

    $videos = get_field('video_suggestion', 'option');

    $args = array('post_type' => 'video', 'fields' => '', 'include' => $videos, 'numberposts' => 0, 'post_status' => 'publish');
    $post = get_line_post($args);

   
    $total['video'] = $post;

}

function suggestion1(){

    global $lines;
    global $total;

    $genres = get_field('to_genre_suggestion', 'option');
    foreach ($genres as $key => $item) {

        $image = get_field('image', 'term_' . $item->term_id)['url'];
        $line = array(
            'id' => $item->term_id, 
            'name'=> $item->name, 
            'slug' => $item->slug, 
            'acf' => array( 
                'image' => array( 
                    'url' => $image 
                )
            )
        );

        array_push($lines, $line);
    }

    $total['genre'] = $lines;
}




