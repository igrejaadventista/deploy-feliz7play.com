<?php

add_action('rest_api_init', function(){
    register_rest_route('wp/v3', '/suggestion', [
        'methods' => 'GET',
	    'callback' => function () {
            $genre = [];
            $genre_suggestion = get_field('to_genre_suggestion', 'option') ?: [];
            foreach ($genre_suggestion as $key => $term) {
                $languages = get_field('languages', $term);
	            if (!empty($languages)) {
		            $filtered_languages = [];

                    foreach ($languages as $language) {
                        $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
                    }
                }

                array_push($genre, [
                    'id' => $term->term_id,
                    'image' => [
                        'url' => isset(get_field('image', $term)['url']) ? get_field('image', $term)['url'] : '',
                    ],
                    'languages' => !empty($languages) ? $filtered_languages : 'Genre languages not found.',
                ]);
            }

            $videos = get_line_post([
                'post_type' => 'video',
                'include' => get_field('video_suggestion', 'option') ?: [],
                'numberposts' => 0,
                'post_status' => 'publish'
            ]);

            return new WP_REST_Response([
                'genre' => $genre,
                'video' => $videos,
            ], 200);
        },
    ]);
});
