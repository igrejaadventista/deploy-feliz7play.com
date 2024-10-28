<?php

if (function_exists('acf_add_options_page')) {
	acf_add_options_sub_page([
		'page_title' 	=> 'Site Settings',
		'menu_title'	=> 'SIte Settings',
		'parent_slug'	=> 'f7p-general-settings',
        'post_id'       => 'site_settings',
        'capability' 	=> 'add_users'
	]);
}

add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/site-settings', [
		'methods' => 'GET',
        'callback' => function () {
			return new WP_REST_Response(get_sorted_languages('site_settings', 'languages'), 200);
		},
	]);
});
