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
	$args['meta_query'] = [
		'relation' => 'AND',
		[
			'key' => 'languages_0_post_video_type',
			'value' => 'Single',
			'compare' => '=',
		],
	];

	return $args;
}

add_action('acf/save_post', function($post_id) {
	$languages = get_field('languages', $post_id);

	if (!$languages) {
		return;
	}

	foreach ($languages as $key => $language_data) {
		if (empty($language_data['post_video_length']) || empty($language_data['post_year'])) {
			$video_id = $language_data['post_video_id'];
			if (empty($video_id)) {
				return;
			}

			$video_host = $language_data['post_video_host'];
			$response = wp_remote_get('https://api.feliz7play.com/v4/' . ($video_host === 'Youtube' ? 'youtubeinfo' : 'vimeoinfo') . '/?video_id=' . $video_id);
			if (is_wp_error($response)) {
				return;
			}

			$video_data = json_decode(wp_remote_retrieve_body($response));
			if ($video_data && property_exists($video_data, 'time') && property_exists($video_data, 'release_date')) {
				$time = $video_data->time;
				$release_year = date('Y', strtotime($video_data->release_date));

				if ($time && $release_year) {
					update_field('languages_' . $key . '_post_video_length', $time, $post_id);
					update_field('languages_' . $key . '_post_year', $release_year, $post_id);
				}
			}
		}
	}
});

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
	$langByCookie = isset($_COOKIE['feliz7playLang']) ? $_COOKIE['feliz7playLang'] : 'pt';

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

function filter_rest_api_response($response) {
	foreach($response->get_links() as $key => $value) {
		$response->remove_link($key);
	}

	if (isset($response->data['acf']['image']) && !empty($response->data['acf']['image'])) {
		$response->data['acf']['image'] = wp_get_attachment_url($response->data['acf']['image'], 'full');
	}

	$languages = $response->data['acf']['languages'];
	if (isset($languages) && !empty($languages)) {
		$filtered_languages = [];

		foreach ($languages as $language) {
			$current_language = $language['language'];
			$filtered_languages[$current_language] = array_diff_key($language, ['language' => '']);

			// Retorna a URL da imagem ao invés do ID
			foreach (['video_thumbnail', 'image_content_header', 'video_image_hover', 'collection_image', 'collection_image_header'] as $image_field) {
				if (isset($language[$image_field]) && !empty($language[$image_field])) {
					$filtered_languages[$current_language][$image_field] = wp_get_attachment_url($language[$image_field], 'full');
				}
			}

			// Retorna o objeto da taxonomia de acordo com o idioma ao invés do ID
			foreach (['collection_category', 'collection_genre'] as $taxonomy_field) {
				if (isset($language[$taxonomy_field]) && !empty($language[$taxonomy_field])) {
					$taxonomy_data = [];

					if (is_array($language[$taxonomy_field])) {
						foreach ($language[$taxonomy_field] as $term_id) {
							$term = get_term($term_id);
							$term_languages = get_field('languages', $term) ?: [];
							foreach ($term_languages as $term_language) {
								if ($term_language['language'] === $current_language) {
									unset($term_language['language']);
									$term->name = $term_language['title'];
									$term->slug = $term_language['slug'];
									$term->description = $term_language['description'];
									$taxonomy_data[] = $term;
								}
							}
						}
					} else {
						$term = get_term($language[$taxonomy_field]);
						$term_languages = get_field('languages', $term) ?: [];
						foreach ($term_languages as $term_language) {
							if ($term_language['language'] === $current_language) {
								unset($term_language['language']);
								$term->name = $term_language['title'];
								$term->slug = $term_language['slug'];
								$term->description = $term_language['description'];
								$taxonomy_data = $term;
							}
						}
					}

					$filtered_languages[$current_language][$taxonomy_field] = $taxonomy_data;
				}
			}
		}

		$response->data['acf']['languages'] = $filtered_languages;
		unset($response->data['slug']);
	}

	return $response;
}

add_filter('rest_prepare_video', 'filter_rest_api_response', 10, 3);
add_filter('rest_prepare_genre', 'filter_rest_api_response', 10, 3);
add_filter('rest_prepare_collection', 'filter_rest_api_response', 10, 3);
add_filter('rest_prepare_category', 'filter_rest_api_response', 10, 3);

// Remove empty collections from the json response
add_filter('rest_post_dispatch', function ($response, $server, $request) {
	if ($request->get_route() === '/wp/v2/collection') {
		if (!is_wp_error($response) && isset($response->data)) {
			$filtered_data = array_filter($response->data, function ($term) {
				if ($term['count'] > 0) {
					return $term;
				}

				$children = get_terms([
					'taxonomy' => 'collection',
					'parent' => $term['id'],
					'hide_empty' => false,
				]);

				foreach ($children as $child) {
					if ($child->count > 0) {
						return $term;
					}
				}

				return;
			});

			$response->data = array_values($filtered_data);
		}
	}

	return $response;
}, 10, 3);
