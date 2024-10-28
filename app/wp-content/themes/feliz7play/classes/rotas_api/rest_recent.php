<?php

add_action('rest_api_init', function() {
	register_rest_route( 'wp/v3', '/recent', [
        'methods' => 'GET',
        'callback' => function ($data) {
            $per_page = $data->get_param('per_page');
            $limited = is_null($per_page) ? 10 : $per_page;
            $args = [
                'post_type'      => 'video',
                'posts_per_page' => 50,
                'post_status'    => 'publish',
            ];

            return new WP_REST_Response(get_line_post($args, $limited), 200 );
        },
    ]);
});
