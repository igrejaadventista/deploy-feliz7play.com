<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");
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
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_liners_v2.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_category.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_collection.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_genre.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_timestamp.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_suggestion.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_recent.php');
require_once (dirname(__FILE__) . '/classes/rotas_api/rest_grid.php');

require_once (dirname(__FILE__) . '/classes/controllers/F7P_Algolia.class.php');

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

add_filter('acf/fields/post_object/query/name=slider_video_object', 'my_acf_fields_post_result', 10, 4);
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
	$langByCookie = isset($_COOKIE['feliz7playLang']) ? $_COOKIE['feliz7playLang'] : '';

	if (!empty($langByCookie)) {
		return $langByCookie;
	}

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

// Função que torna taxonomia Category como radio buttons
function custom_taxonomy_radio_buttons() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#categorychecklist input[type="checkbox"]').each(function() {
				// Verifica se a categoria está selecionada e marca o radio button correspondente
				$checked = '';
				if ($(this).prop('checked')) {
					$checked = 'checked="checked"';
				}
				// Substitui o checkbox por um radio button
				$(this).replaceWith('<input type="radio" name="post_category[]" value="' + $(this).val() + '" ' + $checked + '/>');

            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'custom_taxonomy_radio_buttons');

function getTermsByLanguage($termName) {
	$terms = get_terms($termName);
	$termsLanguage = [];

    foreach ($terms as $term){
        foreach (get_field('languages', $term) as $termLang) {
            $termsLanguage[strtoupper($termLang['language'])][] = [
                'title' => $termLang['title'],
                'slug' => $termLang['slug'],
            ];
        }
    }

	return $termsLanguage;
}

function getActiveImage($lang) {
	$url = '';
	$mainMenu = get_field('languages', 'main_menu');

	foreach ($mainMenu as $language) {
        if ($lang == $language['language']) {
            return $language['language_image']['url'];
        }
    }
}

function filter_languages_response($response) {
	$languages = $response->data['acf']['languages'];
	if (isset($languages) && !empty($languages)) {
		$filtered_languages = [];

		foreach ($languages as $language) {
			$filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
		}

		$response->data['acf']['languages'] = $filtered_languages;
		unset($response->data['slug']);
	}
	return $response;
}

add_filter('rest_prepare_video', 'filter_languages_response', 10, 3);
add_filter('rest_prepare_genre', 'filter_languages_response', 10, 3);
add_filter('rest_prepare_collection', 'filter_languages_response', 10, 3);
add_filter('rest_prepare_category', 'filter_languages_response', 10, 3);

function upload_file_by_url( $image_url ) {

	// it allows us to use download_url() and wp_handle_sideload() functions
	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	// download to temp dir
	$temp_file = download_url( $image_url );

	if( is_wp_error( $temp_file ) ) {
		return false;
	}

	// move the temp file into the uploads directory
	$file = array(
		'name'     => basename( $image_url ),
		'type'     => mime_content_type( $temp_file ),
		'tmp_name' => $temp_file,
		'size'     => filesize( $temp_file ),
	);
	$sideload = wp_handle_sideload(
		$file,
		array(
			'test_form'   => false // no needs to check 'action' parameter
		)
	);

	if( ! empty( $sideload[ 'error' ] ) ) {
		// you may return error message if you want
		return false;
	}

	// it is time to add our uploaded image into WordPress media library
	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => $sideload[ 'url' ],
			'post_mime_type' => $sideload[ 'type' ],
			'post_title'     => basename( $sideload[ 'file' ] ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$sideload[ 'file' ]
	);

	if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return false;
	}

	// update medatata, regenerate image sizes
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )
	);

	return $attachment_id;

}

function get_attachment_id_by_name($filename) {
	global $wpdb;

	$query = $wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s", '%' . $wpdb->esc_like($filename) . '%');
	$attachment_id = $wpdb->get_var($query);

	if ($attachment_id) {
		$attachment_url = wp_get_attachment_url($attachment_id);
		if ($attachment_url && strpos($attachment_url, $filename) !== false) {
			return $attachment_id;
		}
	}

	return false;
}

function clear_content() {
	$videos = get_posts([
		'post_type' => ['video', 'attachment'],
		'posts_per_page' => -1,
		'post_status' => ['any', 'publish', 'trash'],
		'fields' => 'ids'
	]);

	foreach ($videos as $video_id) {
		wp_delete_post($video_id, true);
	}

	foreach (['genre', 'collection', 'category'] as $taxonomy) {
		$terms = get_terms([
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		]);

		foreach ($terms as $term) {
			wp_delete_term($term->term_id, $term->taxonomy);
		}
	}
}

function import_category_terms() {
	$categories = [
		[
			// extras
			'pt' => 1,
			'es' => 1,
		],
		[
			// filmes/peliculas
			'pt' => 590,
			'es' => 633,
		],
		[
			// infantil/ninos
			'pt' => 591,
			'es' => 631,
		],
		[
			// musicas
			'pt' => 593,
			'es' => 632,
		],
		[
			// series
			'pt' => 592,
			'es' => 634,
		],
	];

	foreach ($categories as $category) {
		$current_term = null;

		foreach ($category as $language => $id) {
			$response = wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/category/{$id}");
			$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);

			if ($language === 'pt') {
				$term = wp_insert_term($data['name'], 'category');
				if (!is_wp_error($term)) {
					$current_term = get_term($term['term_id'], 'category');
				}
			}

			if ($current_term !== null) {
				$row = [
					'language' => $language,
					'title' => $data['name'],
					'slug' => $data['slug'],
					'description' => $data['description'],
					...$data['acf'],
				];

				add_row('field_671244c1d177f', $row, $current_term);

				add_term_meta($current_term->term_id, 'old_id_' . $language, $id);
			}
		}

		$current_term = null;
	}
}

function import_collection_terms() {
	$collections = [
		[
			'pt' => 101,
			'es' => 162,
		],
		[
			'pt' => 433,
			'es' => 550,
		],
		[
			'pt' => 546,
			'es' => 610,
		],
		[
			'pt' => 48,
			'es' => 44,
		],
		[
			'pt' => 610,
			'es' => 656,
		],
	];

	foreach ($collections as $collection) {
		$current_term = null;

		foreach ($collection as $language => $id) {
			$response = wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/collection/{$id}");
			$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);

			foreach (['collection_image', 'collection_image_header'] as $image_field) {
				$url = isset($data['acf'][$image_field]['url']) ? $data['acf'][$image_field]['url'] : '';
				if (!empty($url)) {
					$filename = $data['acf'][$image_field]['filename'];
					$file_id = get_attachment_id_by_name($filename) ?: upload_file_by_url($url);
					$data['acf'][$image_field] = $file_id;
				}
			}

			foreach (['collection_category', 'collection_genre'] as $taxonomy_field) {
				$current_taxonomy_field = isset($data['acf'][$taxonomy_field]) ? $data['acf'][$taxonomy_field] : '';

				if (!empty($current_taxonomy_field)) {
					$terms = [];

					if (count($current_taxonomy_field) == count($current_taxonomy_field, COUNT_RECURSIVE)) {
						$term = get_term_by('slug', $current_taxonomy_field['slug'], $current_taxonomy_field['taxonomy']);
						if (!is_wp_error($term)) {
							$terms = $term->term_id;
						}
					} else {
						foreach ($current_taxonomy_field as $term_data) {
							$term = get_term_by('slug', $term_data['slug'], $term_data['taxonomy']);
							if (!is_wp_error($term)) {
								$terms[] = $term->term_id;
							}
						}
					}

					$data['acf'][$taxonomy_field] = $terms;
				}
			}

			if ($language === 'pt') {
				$args = [];

				if ($data['parent'] !== 0) {
					$parent_data = json_decode(wp_remote_get("https://test-f7p.internetdsa.com/{$language}/e/wp-json/wp/v2/collection/{$data['parent']}")['body'], true, JSON_UNESCAPED_SLASHES);
					if (!empty($parent_data)) {
						$args['parent'] = get_term_by('slug', $parent_data['slug'], 'collection')->term_id;
					}
				}

				$term = wp_insert_term($data['name'], 'collection', $args);
				$current_term = get_term($term['term_id'], 'collection');
			}

			if ($current_term !== null) {
				$row = [
					'language' => $language,
					'title' => $data['name'],
					'slug' => $data['slug'],
					'description' => $data['description'],
					...$data['acf'],
				];

				add_row('field_6712409cbf2d8', $row, $current_term);

				add_term_meta($current_term->term_id, 'old_id_' . $language, $id);
			}
		}

		$current_term = null;
	}
}

function import_genre_terms() {
	$genres = [
		[
			'pt' => 599,
			'es' => 650,
		],
		[
			'pt' => 2,
			'es' => 647,
		],
		[
			'pt' => 596,
			'es' => 653,
		],
		[
			'pt' => 18,
			'es' => 648,
		],
		[
			'pt' => 531,
			'es' => 664,
		],
		[
			'pt' => 600,
			'es' => 651,
		],
		[
			'pt' => 5,
			'es' => 3,
		],
		[
			'pt' => 6,
			'es' => 5,
		],
		[
			'pt' => 598,
			'es' => 649,
		],
		[
			'pt' => 579,
			'es' => 624,
		],
		[
			'pt' => 7,
			'es' => 652,
		],
		[
			'pt' => 602,
			'es' => 662,
		],
		[
			'pt' => 529,
			'es' => 658,
		],
		[
			'pt' => 431,
			'es' => 597,
		],
		[
			'pt' => 431,
			'es' => 597,
		],
		[
			'pt' => 10,
			'es' => 7,
		],
		[
			'pt' => 11,
			'es' => 642,
		],
		[
			'pt' => 597,
			'es' => 637,
		],
		[
			'pt' => 532,
			'es' => 657,
		],
		[
			'pt' => 603,
			'es' => 663,
		],
		[
			'pt' => 468,
			'es' => 659,
		],
		[
			'pt' => 604,
			'es' => 641,
		],
		[
			'pt' => 12,
			'es' => 660,
		],
		[
			'pt' => 13,
			'es' => 11,
		],
		[
			'pt' => 530,
			'es' => 661,
		],
		[
			'pt' => 17,
			'es' => 655,
		],
		[
			'pt' => 20,
			'es' => 15,
		],
	];

	foreach ($genres as $genre) {
		$current_term = null;

		foreach ($genre as $language => $id) {
			$response = wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/genre/{$id}");
			$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);

			if ($language === 'pt') {
				$term = wp_insert_term($data['name'], 'genre');
				if (!is_wp_error($term)) {
					$current_term = get_term($term['term_id'], 'genre');
				}
			}

			if ($current_term !== null) {
				$image_url = isset($data['acf']['image']['url']) ? $data['acf']['image']['url'] : '';
				if (!empty($image_url)) {
					$filename = $data['acf']['image']['filename'];
					$file_id = get_attachment_id_by_name($filename) ?: upload_file_by_url($image_url);
					update_field('field_6022ce8e5a16f', $file_id, $current_term);
				}

				$row = [
					'language' => $language,
					'title' => $data['name'],
					'slug' => sanitize_title($data['name']),
				];

				add_row('field_6706bd52aa917', $row, $current_term);

				add_term_meta($current_term->term_id, 'old_id_' . $language, $id);
			}
		}

		$current_term = null;
	}
}

function import_videos() {
	$posts = [
		[
			'pt' => 10283,
			'es' => 8694,
		],
		[
			'pt' => 10282,
			'es' => 8693,
		],
		[
			'pt' => 5108,
			'es' => 2751,
		],
		[
			'pt' => 5114,
			'es' => 2761,
		],
		[
			'pt' => 11447,
			'es' => 9254,
		],
		[
			'pt' => 7831,
			'es' => 7311,
		],
	];

	foreach ($posts as $post) {
		foreach ($post as $language => $id) {
			$response = wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/video/{$id}");
			$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);
			$title = $data['title']['rendered'];

			foreach (['video_thumbnail', 'video_image_hover','image_content_header'] as $image_field) {
				$url = isset($data['acf'][$image_field]['url']) ? $data['acf'][$image_field]['url'] : '';
				if (!empty($url)) {
					$filename = $data['acf'][$image_field]['filename'];
					$file_id = get_attachment_id_by_name($filename) ?: upload_file_by_url($url);
					$data['acf'][$image_field] = $file_id;
				}
			}

			if ($language === 'pt' && !post_exists($title)) {
				$new_video = [
					'post_title' => $title,
					'post_type' => 'video',
					'post_status' => 'publish',
				];
				$video_id = wp_insert_post($new_video);
			}

			if ($video_id) {
				$row = [
					'language' => $language,
					'title' => $title,
					'slug' => $data['slug'],
					...$data['acf'],
				];

				add_row('field_670ff24637fba', $row, $video_id);

				if (isset($data['taxonomies']) && is_array($data['taxonomies'])) {
					foreach ($data['taxonomies'] as $taxonomy => $terms) {
						if (is_array($terms)) {
							foreach ($terms as $term_data) {
								$current_terms = get_the_terms($video_id, $taxonomy) ?: [];
								if (!empty($current_terms)) {
									$current_terms = wp_list_pluck($current_terms, 'term_id');
								}

								$term = get_term_by('slug', $term_data['slug'], $taxonomy);

								if ($term) {
									wp_set_post_terms($video_id, [$term->term_id, ...$current_terms], $taxonomy);
								}
							}
						}
					}
				}

				$lang_audio_term = get_term_by('slug', $language, 'language_audio');
				$lang_current_terms = get_the_terms($video_id, 'language_audio') ?: [];
				if (!empty($lang_current_terms)) {
					$lang_current_terms = wp_list_pluck($lang_current_terms, 'term_id');
				}
				wp_set_post_terms($video_id, [$lang_audio_term->term_id, ...$lang_current_terms], 'language_audio');

				add_post_meta($video_id, 'old_id_' . $language, $id, true);
			}
		}
	}
}

// add_action('admin_init', 'clear_content');
// add_action('admin_init', 'import_category_terms');
// add_action('admin_init', 'import_genre_terms');
// add_action('admin_init', 'import_collection_terms');
// add_action('admin_init', 'import_videos');
// var_dump(get_attachment_id_by_name('Miniatura_Provai_e_Vede_2024_Eps1.jpg'));
// die();
// videos
// var_dump(get_post_meta(659, 'old_id_pt', true));
// var_dump(get_post_meta(659, 'old_id_es', true));
// die();
// terms
// var_dump(get_term_meta(250, 'old_id_pt', true));
// var_dump(get_term_meta(250, 'old_id_es', true));
// die();
