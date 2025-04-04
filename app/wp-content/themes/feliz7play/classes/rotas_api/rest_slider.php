<?php

add_action('rest_api_init', function () {
	register_rest_route('wp/v3', '/slider', [
		'methods' => 'GET',
		'callback' => function () {
			$sliders = [];
			$items = get_field('sliders', 'option');

			foreach ($items as $item) {
				array_push($sliders, get_slider_infos($item['slider_object']));
			}

			return new WP_REST_Response($sliders, 200);
		}
	]);
});
