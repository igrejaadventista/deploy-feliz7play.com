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

    $itens_large_right = get_field('grid_large_right', 'option');
    $itens_medium_left = get_field('grid_medium_left', 'option');
    $itens_top_small_right = get_field('grid_top_small_right', 'option');
    $itens_top_small_left = get_field('grid_top_small_left', 'option');
    $itens_bottom_small_right = get_field('grid_bottom_small_right', 'option');
    $itens_bottom_small_left = get_field('grid_bottom_small_left', 'option');

    $grid = [
        'large_right' => $itens_large_right,
        'medium_left' => $itens_medium_left,
        'top_small_right' => $itens_top_small_right,
        'top_small_left' => $itens_top_small_left,
        'bottom_small_right' => $itens_bottom_small_right,
        'bottom_small_left' => $itens_bottom_small_left
    ];

    $total['grid'] = $grid;
}




