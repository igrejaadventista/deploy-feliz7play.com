<?php

add_action('rest_api_init', function() {
	register_rest_route( 'wp/v3', '/grid', [
        'methods' => 'GET',
	    'callback' => function () {
            $field_ids = [
                'large_right',
                'medium_left',
                'top_small_right',
                'top_small_left',
                'bottom_small_right',
                'bottom_small_left'
            ];

            $data = [];

            foreach ($field_ids as $id) {
                $prefix = 'grid_' . $id;
                $field = get_field($prefix, 'option');

                $genre = $field[$prefix . '_genre'];
                if (!empty($genre)) {
                    $genre_languages = get_field('languages', $genre);

                    if (!empty($genre_languages)) {
                        $filtered_languages = [];

                        foreach ($genre_languages as $language) {
                            $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
                        }
                    }

                    $field[$prefix . '_genre']->languages = !empty($genre_languages) ? $filtered_languages : 'Genre languages not found.';
                }

                $languages = $field[$prefix . '_languages'] ?: [];
                if (!empty($languages)) {
                    foreach ($languages as $key => $language) {
                        $languages[$language['language']] = array_diff_key($language, ['language' => '']);
                        unset($languages[$key]);
                    }

                    $field[$prefix . '_languages'] = $languages;
                }

                foreach ($field as $key => $value) {
                    $sufix = explode('_', $key);
    	            $sufix = trim(end($sufix));
                    $field[$sufix] = $value;
                    unset($field[$key]);
                }

                $data[$id] = $field;
            }

            return new WP_REST_Response($data , 200);
        },
    ]);
});



