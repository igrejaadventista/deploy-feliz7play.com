<?php

require_once (dirname(__FILE__) . '/classes/controllers/F7P_Theme_Helper.class.php');
require_once (dirname(__FILE__) . '/classes/controllers/F7P_TAX_Custom.class.php');
require_once (dirname(__FILE__) . '/classes/controllers/F7P_API_Rest.class.php');
require_once (dirname(__FILE__) . '/classes/controllers/F7P_CPT_Video.class.php');
require_once (dirname(__FILE__) . '/classes/controllers/F7P_CPT_Slider.class.php');
require_once (dirname(__FILE__) . '/classes/controllers/F7P_Site_Settings.class.php');


// Rotas API

require_once (dirname(__FILE__) . '/classes/rotas_api/functions_rest.php');

require_once (dirname(__FILE__) . '/classes/rotas_api/rest_slider.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_liners.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_collection.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_genre.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_timestamp.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_suggestion.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_recent.php');

function curl_error_60_workaround( $handle, $r, $url ) {

    // Disable peer verification to temporarily resolve error 60.
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

}

add_action( 'http_api_curl', 'curl_error_60_workaround', 10, 3 );

// disable generated image sizes
function shapeSpace_disable_image_sizes($sizes) {
	
	unset($sizes['thumbnail']);    // disable thumbnail size
	unset($sizes['medium']);       // disable medium size
	unset($sizes['large']);        // disable large size
	unset($sizes['medium_large']); // disable medium-large size
	unset($sizes['1536x1536']);    // disable 2x medium-large size
	unset($sizes['2048x2048']);    // disable 2x large size
	
	return $sizes;
	
}
add_action('intermediate_image_sizes_advanced', 'shapeSpace_disable_image_sizes');

// disable scaled image size
add_filter('big_image_size_threshold', '__return_false');

// disable other image sizes
function shapeSpace_disable_other_image_sizes() {
	
	remove_image_size('post-thumbnail'); // disable images added via set_post_thumbnail_size() 
	remove_image_size('another-size');   // disable any other added image sizes
	
}
add_action('init', 'shapeSpace_disable_other_image_sizes');

add_filter('acf/fields/taxonomy/query/name=to_collection', 'my_acf_fields_taxonomy_result', 10, 4);
add_filter('acf/fields/taxonomy/query/name=to_custom_collection', 'my_acf_fields_taxonomy_result', 10, 4);
function my_acf_fields_taxonomy_result( $args ) {
	
	$args['posts_per_page'] = 40;
	$args['parent'] = 0;

	return $args;
}

add_filter('acf/fields/taxonomy/query/name=to_genre_suggestion', 'my_acf_fields_genre_result', 10, 4);
function my_acf_fields_genre_result( $args) {
	
    $args['hide_empty'] = true;
	
	return $args;
}

add_filter('acf/fields/post_object/query/name=to_video', 'my_acf_fields_post_result', 10, 4);
function my_acf_fields_post_result( $args) {
	
	$args['posts_per_page'] = 40;
	$args['meta_key'] = 'post_video_type';
    $args['meta_value'] = 'Single';
	$args['post_status'] = 'publish';
	
	return $args;
}


function getVideoInfo($post_id, $video_host, $video_id){

	$data =  array($post_id, $video_host, $video_id);

	// SET VIDEO LENGHT BY VIEMO/YOUTUBE API
	switch ($video_host) {
		case "Youtube":
			$json = file_get_contents("https://api.feliz7play.com/v4/youtubeinfo?video_id=". $video_id );
			$obj = json_decode($json);
			
			$time = $obj->time;
			$release_year = date('Y', strtotime($obj->release_date));

			if ($obj) {
				update_field( 'post_video_length', $time, $post_id );
				update_field( 'post_video_year', $release_year, $post_id );
			} 

			unset($json, $obj, $time, $size, $release_year);
			break;
			
		case "Vimeo":
			$json = file_get_contents("https://api.feliz7play.com/v4/vimeoinfo?video_id=". $video_id);
			$obj = json_decode($json);

			$time = $obj->time;
			$release_year = date('Y', strtotime($obj->release_date));

			if ($time) {
				update_field( 'post_video_length', $time, $post_id );
				update_field( 'post_video_year', $release_year, $post_id );
			} 
			
			unset($json, $obj, $time, $release_year);
			break;
	}

	
}

function UpdateVideoLenght( $post_id ) {
	$video_host = get_field("post_video_host", $post_id);
	$video_id = get_field("post_video_id", $post_id);
	$video_lenght = get_field("post_video_length", $post_id);
	$release_year = get_field("post_video_year", $post_id);

	$data = array($post_id, $video_host, $video_lenght, $release_year);

	if ( !$video_lenght || !$release_year ){
		getVideoInfo( $post_id, $video_host, $video_id );
	}

	//RESET CF CACHE
	$json = file_get_contents("https://api.feliz7play.com/v4/clear-cf-cache?zone=feliz7play.com");
	$obj = json_decode($json);

	unset($json, $obj, $data);
}
add_action( 'acf/save_post', 'UpdateVideoLenght' );


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'F7P - Settings',
		'menu_slug' 	=> 'f7p-general-settings',
		// 'capability' 	=> 'add_users',
		'icon_url' 		=> 'dashicons-admin-tools',
	));
	
}

function enqueueAssets() {
	wp_enqueue_style('fonts', '//fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap', false, null);
    wp_enqueue_style('main', get_template_directory_uri() . '/dist/styles/main.css', false, null);

	wp_enqueue_script('main', get_template_directory_uri() . '/dist/scripts/main.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueueAssets');

function getUser() {
	return [
		'name' => isset($_COOKIE['feliz7playName']) ? $_COOKIE['feliz7playName'] : '',
		'avatar' => isset($_COOKIE['feliz7playAvatar']) ? $_COOKIE['feliz7playAvatar'] : '',
	];
}

function getLanguage() {
	$lang = wp_parse_url(home_url())['path'];
    $lang = explode('/', $lang);

    return !empty($lang) ? $lang[1] : '';
}


//Função auxiliar para imprimir no console o print_r.
function pconsole($var) {

    $s = json_encode($var);
    echo "<script>console.log(". $s . ");</script>";
    return;
}
// Função auxiliar para imprimir no console o echo.
function cconsole($var) {

    echo "<script>console.log('" . $var . "');</script>";
    return;
}