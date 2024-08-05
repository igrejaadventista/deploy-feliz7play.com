<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/grid', array(
	'methods' => 'GET',
	'callback' => 'get_page_option_grid',
	));
});

$lines = array();
$total = array();

function get_page_option_grid($data) {
	
    global $total;
    
    get_grid();
           
    
	return new WP_REST_Response($total , 200 );
}



function get_grid(){

    global $lines;
    global $total;

    // $grid_top_left = get_field('grid_top_left', 'option');

    $grid_title = get_sub_field('grid_top_left_title');
    $grid_genre = get_field('grid_top_left_genre');

    $grid_data = [
        'title' => $grid_title, 
        'genre' => $grid_genre,
    ];

        // array_push($lines, $line);

    // $print = print_r($grid_top_left, true);

    $total['grid']['top_left'] = $grid_data;
}




