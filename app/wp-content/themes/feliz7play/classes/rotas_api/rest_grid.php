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

    $itens_top_left = get_field('grid_top_left', 'option');
    $itens_top_right = get_field('grid_top_right', 'option');

    $grid = [
        'top_left' => $itens_top_left,
        'top_right' => $itens_top_right,
        // 'bottom_left' => $itens_bottom_left,
        // 'bottom_right' => $itens_bottom_right
    ];

    $total['grid'] = $grid;
}




