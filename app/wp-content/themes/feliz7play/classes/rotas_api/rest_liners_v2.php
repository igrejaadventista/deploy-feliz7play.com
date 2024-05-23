<?php
  
add_action( 'rest_api_init', function(){
	register_rest_route( 'wp/v3', '/line-v2', array(
	'methods' => 'GET',
	'callback' => 'get_page_option_line_v2',
	));
});

$lines = array();

function get_page_option_line_v2($data) {
	
	global $lines;
	
	$itens = have_rows('lines');

	if(have_rows('lines', 'option')){

		while(have_rows('lines', 'option')) : the_row();

			switch (get_row_layout()) {
				case 'genre_option': 		
					get_genre_v2(get_sub_field('genre')); 
					break;
				case 'collection_option': 	get_collection(get_sub_field('to_collection')); break;
				case 'custom_option': 		get_custom(get_sub_field('custom')); break;
				case 'recentes_option': 	get_recentes(); break;
				default: 					echo 'erro!'; die; break;
			}
		endwhile;
	}
	
	return new WP_REST_Response($lines, 200 );
}

