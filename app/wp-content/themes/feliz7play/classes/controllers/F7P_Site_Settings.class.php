<?php


if( function_exists('acf_add_options_page') ) {
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Site Settings',
		'menu_title'	=> 'SIte Settings',
		'parent_slug'	=> 'f7p-general-settings',
        'post_id'       => 'site_settings',
        'capability' 	=> 'add_users'
	));
}

add_action( 'rest_api_init', 'rest_site_settings' );
function rest_site_settings() {
    register_rest_route( 'wp/v2', '/site-settings', array(
        'methods' => 'GET',
        'callback' => 'get_site_settings',
    ) );
}

function get_site_settings() {
    $languages = get_field('languages', 'site_settings');

	if (!empty($languages)) {
        $filtered_languages = [];
		foreach ($languages as $language) {
			$filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
		}
	}

    return new WP_REST_Response(isset($filtered_languages) && !empty($filtered_languages) ? $filtered_languages : $languages, 200);
}
