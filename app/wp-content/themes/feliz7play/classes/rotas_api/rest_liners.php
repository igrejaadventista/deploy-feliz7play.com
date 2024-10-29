<?php

add_action('rest_api_init', function() {
	register_rest_route('wp/v3', '/line', [
		'methods' => 'GET',
		'callback' => function($data) {
			$data = [];

			if (have_rows('lines', 'option')) {
				while (have_rows('lines', 'option')) {
					the_row();

					// verificar quais campos precisam ser traduzidos na options page
					switch (get_row_layout()) {
						// Ok
						case 'genre_option':
							array_push($data, get_genre(get_sub_field('genre')));
							break;

						// Add Languages
						case 'collection_option':
							array_push($data, get_collection(get_sub_field('to_collection')));
							break;

						// Debug (código comentado)
						case 'custom_option':
							array_push($data, get_custom(get_sub_field('custom')));
							break;

						// Ok
						case 'recentes_option':
							array_push($data, get_recentes());
							break;
					}
				}
			}

			return new WP_REST_Response($data, 200);
		},
	]);
});

