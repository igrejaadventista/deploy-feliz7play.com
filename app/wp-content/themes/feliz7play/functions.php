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
		if (!empty($language_data['title']) && empty($language_data['slug'])) {
			$slug = sanitize_title($language_data['title']);
			update_field('languages_' . $key . '_slug', $slug, $post_id);
		}

		if (empty($language_data['post_video_length']) || empty($language_data['post_year'])) {
			$video_id = $language_data['post_video_id'];
			if (!empty($video_id)) {
				$video_host = $language_data['post_video_host'];
				$response = wp_remote_get('https://api.feliz7play.com/v4/' . ($video_host === 'Youtube' ? 'youtubeinfo' : 'vimeoinfo') . '/?video_id=' . $video_id);
				if (!is_wp_error($response)) {
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

function filter_rest_api_response($response, $post, $request) {
	foreach($response->get_links() as $key => $value) {
		$response->remove_link($key);
	}

	if (isset($response->data['acf']['image']) && !empty($response->data['acf']['image'])) {
		$response->data['acf']['image'] = wp_get_attachment_url($response->data['acf']['image']);
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
					$filtered_languages[$current_language][$image_field] = wp_get_attachment_url($language[$image_field]);
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

			// Filter extra fields
			$extra_fields = [
				'extra' => 'extra_list_',
				'post_extra' => 'post_extra_list_',
			];

			foreach ($extra_fields as $extra_field_key => $extra_field_prefix) {
				if (isset($language[$extra_field_key]) && !empty($language[$extra_field_key])) {
					unset($filtered_languages[$current_language][$extra_field_key]);

					foreach ($language[$extra_field_key] as $extra_key => $extra_field) {
						$videos = [];
						if (is_array($extra_field[$extra_field_prefix . 'videos'])) {
							foreach ($extra_field[$extra_field_prefix . 'videos'] as $extra_video) {
								$video = get_post($extra_video[$extra_field_key . '_video']);
								$videos[] = get_post_infos($video);
							}
						}

						$filtered_languages[$current_language]['extras'][] = [
							'title' => $extra_field[$extra_field_key . '_title'],
							'videos' => $videos,
						];
					}
				}
			}

			// Filter social media
			if (isset($language['redes']) && !empty($language['redes'])) {
				foreach ($language['redes'] as $social_key => $social) {
					if (isset($social['name_rede'])) {
						$filtered_languages[$current_language]['redes'][$social_key]['name'] = $language['redes'][$social_key]['name_rede'];
						unset($filtered_languages[$current_language]['redes'][$social_key]['name_rede']);
					}

					if (isset($social['url_rede'])) {
						$filtered_languages[$current_language]['redes'][$social_key]['url'] = $language['redes'][$social_key]['url_rede'];
						unset($filtered_languages[$current_language]['redes'][$social_key]['url_rede']);
					}

					$icon_key = isset($social['icone_rede']) ? 'icone_rede' : 'icon_rede';
					if (isset($social[$icon_key])) {
						$filtered_languages[$current_language]['redes'][$social_key]['icon'] = wp_get_attachment_url($language['redes'][$social_key][$icon_key]);
						unset($filtered_languages[$current_language]['redes'][$social_key][$icon_key]);
					}
				}
			}

			$filtered_languages[$current_language]['social_media'] = $filtered_languages[$current_language]['redes'];
			unset($filtered_languages[$current_language]['redes']);
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

	// foreach (['genre', 'collection', 'category'] as $taxonomy) {
	// 	$terms = get_terms([
	// 		'taxonomy'   => $taxonomy,
	// 		'hide_empty' => false,
	// 	]);

	// 	foreach ($terms as $term) {
	// 		wp_delete_term($term->term_id, $term->taxonomy);
	// 	}
	// }
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

			if ($language === array_key_first($category)) {
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

function import_collection_terms($collection = []) {
	$collections = [
		// Batch 1
		// ['pt' => 101, 'es' => 162],
		// ['pt' => 28],
		// ['pt' => 594],
		// ['pt' => 438],
		// ['pt' => 88, 'es' => 161],
		// ['pt' => 179],
		// ['pt' => 49],
		// ['pt' => 557],
		// ['pt' => 176],
		// ['pt' => 87],
		// ['pt' => 496],
		// ['pt' => 155],
		// ['pt' => 140],
		// ['pt' => 447, 'es' => 229],
		// ['pt' => 86],
		// ['pt' => 156],
		// ['pt' => 173],
		// ['pt' => 135],
		// ['pt' => 96],
		// ['pt' => 100, 'es' => 187],
		// ['pt' => 64, 'es' => 139],
		// ['pt' => 34],
		// ['pt' => 77],
		// ['pt' => 42],
		// ['pt' => 51],
		// ['pt' => 150],
		// ['pt' => 174],
		// ['pt' => 121, 'es' => 152],
		// ['pt' => 170],
		// ['pt' => 143],
		// ['pt' => 161],
		// ['pt' => 569, 'es' => 201],
		// ['pt' => 58],
		// ['pt' => 132],
		// ['pt' => 31, 'es' => 109],
		// ['pt' => 45],
		// ['pt' => 32, 'es' => 90],
		// ['pt' => 97],
		// ['pt' => 189],
		// ['pt' => 95],
		// ['pt' => 550],
		// ['pt' => 566],
		// ['pt' => 104],
		// ['pt' => 53, 'es' => 16],
		// ['pt' => 583],
		// ['pt' => 107, 'es' => 174],
		// ['pt' => 142],
		// ['pt' => 169],
		// ['pt' => 60],
		// Batch 2
		// ['pt' => 513, 'es' => 615],
		// ['pt' => 520],
		// ['pt' => 560],
		// ['pt' => 43],
		// ['pt' => 471],
		// ['pt' => 91],
		// ['pt' => 39],
		// ['pt' => 160],
		// ['pt' => 607],
		// ['pt' => 154],
		// ['pt' => 78],
		// ['pt' => 538],
		// ['pt' => 146],
		// ['pt' => 130],
		// ['pt' => 416],
		// ['pt' => 103, 'es' => 175],
		// ['pt' => 519],
		// ['pt' => 63, 'es' => 138],
		// ['pt' => 144],
		// ['pt' => 472],
		// ['pt' => 474],
		// ['pt' => 159],
		// ['pt' => 68],
		// ['pt' => 138],
		// ['pt' => 172],
		// ['pt' => 485, 'es' => 579],
		// ['pt' => 423],
		// ['pt' => 515, 'es' => 591],
		// ['pt' => 573],
		// ['pt' => 426],
		// ['pt' => 194],
		// ['pt' => 117],
		// ['pt' => 469],
		// ['pt' => 465],
		// ['pt' => 464],
		// ['pt' => 171],
		// ['pt' => 115],
		// ['pt' => 46],
		// ['pt' => 57],
		// ['pt' => 545],
		// ['pt' => 553],
		// ['pt' => 587],
		// ['pt' => 120],
		// ['pt' => 502],
		// ['pt' => 541],
		// ['pt' => 59],
		// ['pt' => 153],
		// ['pt' => 74],
		// ['pt' => 44],
		// // Batch 3
		// ['pt' => 470],
		// ['pt' => 148],
		// ['pt' => 126],
		// ['pt' => 83],
		// ['pt' => 93],
		// ['pt' => 33],
		// ['pt' => 111],
		// ['pt' => 82, 'es' => 173],
		// ['pt' => 152],
		// ['pt' => 106],
		// ['pt' => 35],
		// ['pt' => 608],
		// ['pt' => 554],
		// ['pt' => 193],
		// ['pt' => 586],
		// ['pt' => 56],
		// ['pt' => 29],
		// ['pt' => 141],
		// ['pt' => 279],
		// ['pt' => 139],
		// ['pt' => 164],
		// ['pt' => 503],
		// ['pt' => 605],
		// ['pt' => 187],
		// ['pt' => 589],
		// ['pt' => 462],
		// ['pt' => 89],
		// ['pt' => 177],
		// ['pt' => 134],
		// ['pt' => 186],
		// ['pt' => 563],
		// ['pt' => 52],
		// ['pt' => 38],
		// ['pt' => 129],
		// ['pt' => 41],
		// ['pt' => 445],
		// ['pt' => 175],
		// ['pt' => 540],
		// ['pt' => 114],
		// ['pt' => 108],
		// ['pt' => 151, 'es' => 165],
		// ['pt' => 184],
		// ['pt' => 480],
		// ['pt' => 508],
		// ['pt' => 37],
		// ['pt' => 69],
		// ['pt' => 71],
		// ['pt' => 79],
		// ['pt' => 466],
		// ['pt' => 80],
		// // Batch 4
		// ['pt' => 137, 'es' => 188],
		// ['pt' => 494],
		// ['pt' => 48, 'es' => 44],
		// ['pt' => 157],
		// ['pt' => 428],
		// ['pt' => 571, 'es' => 626],
		// ['pt' => 549],
		// ['pt' => 509],
		// ['pt' => 98, 'es' => 234],
		// ['pt' => 147],
		// ['pt' => 463],
		// ['pt' => 521],
		// ['pt' => 419],
		// ['pt' => 168, 'es' => 223],
		// ['pt' => 94],
		// ['pt' => 448, 'es' => 546],
		// ['pt' => 455],
		// ['pt' => 167],
		// ['pt' => 165],
		// ['pt' => 72],
		// ['pt' => 544],
		// ['pt' => 145],
		// ['pt' => 535],
		// ['pt' => 158],
		// ['pt' => 112],
		// ['pt' => 565],
		// ['pt' => 125],
		// ['pt' => 47],
		// ['pt' => 116],
		// ['pt' => 533],
		// ['pt' => 99, 'es' => 169],
		// ['pt' => 90],
		// ['pt' => 65, 'es' => 143],
		// ['pt' => 24, 'es' => 18],
		// ['pt' => 76, 'es' => 153],
		// ['pt' => 50, 'es' => 49],
		// ['pt' => 73],
		// ['pt' => 182],
		// ['pt' => 92],
		// ['pt' => 36, 'es' => 78],
		// ['pt' => 26],
		// ['pt' => 183],
		// ['pt' => 180],
		// ['pt' => 54],
		// ['pt' => 166],
		// ['pt' => 504],
		// ['pt' => 488],
		// ['pt' => 542, 'es' => 605],
		// ['pt' => 577],
		// ['pt' => 483],
		// ['pt' => 585],
		// Batch 5
		// ['pt' => 113],
		// ['pt' => 118],
		// ['pt' => 105],
		// ['pt' => 192],
		// ['pt' => 567],
		// ['pt' => 484],
		// ['pt' => 81],
		// ['pt' => 162, 'es' => 214],
		// ['pt' => 136],
		// ['pt' => 84],
		// ['pt' => 149],
		// ['pt' => 109],
		// ['pt' => 75],
		// ['pt' => 188],
		// ['pt' => 181],
		// ['pt' => 433, 'es' => 550],
		// ['pt' => 562, 'es' => 620],
		// ['pt' => 434, 'es' => 549],
		// ['pt' => 555],
		// ['pt' => 556],
		// ['pt' => 246],
		// ['pt' => 201],
		// ['pt' => 467],
		// ['pt' => 514],
		// ['pt' => 606],
		// ['pt' => 432],
		// ['pt' => 55],
		// ['pt' => 131],
		// ['pt' => 128],
		// ['pt' => 127],
		// ['pt' => 226, 'es' => 151],
		// ['pt' => 216, 'es' => 77],
		// ['pt' => 500],
		// ['pt' => 499],
		// ['pt' => 453],
		// ['pt' => 454],
		// ['pt' => 449, 'es' => 548],
		// ['pt' => 510],
		// ['pt' => 450, 'es' => 547],
		// ['pt' => 308],
		// ['pt' => 293],
		// ['pt' => 479],
		// ['pt' => 478],
		// ['pt' => 314, 'es' => 500],
		// ['pt' => 305, 'es' => 494],
		// ['pt' => 491],
		// ['pt' => 561, 'es' => 616],
		// ['pt' => 536],
		// ['pt' => 522],
		// ['pt' => 548, 'es' => 617],
		// // Batch 6
		// ['pt' => 518],
		// ['pt' => 588, 'es' => 629],
		// ['pt' => 570, 'es' => 623],
		// ['pt' => 224],
		// ['pt' => 215],
		// ['pt' => 211],
		// ['pt' => 222],
		// ['pt' => 482],
		// ['pt' => 481],
		// ['pt' => 517, 'es' => 621],
		// ['pt' => 572, 'es' => 622],
		// ['pt' => 516],
		// ['pt' => 575],
		// ['pt' => 574],
		// ['pt' => 551],
		// ['pt' => 552],
		// ['pt' => 487],
		// ['pt' => 486],
		// ['pt' => 568],
		// ['pt' => 582],
		// ['pt' => 473],
		// ['pt' => 367],
		// ['pt' => 337],
		// ['pt' => 286],
		// ['pt' => 317],
		// ['pt' => 296],
		// ['pt' => 295, 'es' => 319],
		// ['pt' => 267, 'es' => 301],
		// ['pt' => 523],
		// ['pt' => 524],
		// ['pt' => 576],
		// ['pt' => 212],
		// ['pt' => 229],
		// ['pt' => 495],
		// ['pt' => 539],
		// ['pt' => 275, 'es' => 362],
		// ['pt' => 274, 'es' => 311],
		// ['pt' => 198, 'es' => 252],
		// ['pt' => 459, 'es' => 552],
		// ['pt' => 537, 'es' => 627],
		// ['pt' => 610, 'es' => 656],
		// ['pt' => 460],
		// ['pt' => 461],
		// ['pt' => 506],
		// ['pt' => 559],
		// ['pt' => 341, 'es' => 449],
		// ['pt' => 493],
		// ['pt' => 335, 'es' => 472],
		// ['pt' => 323, 'es' => 469],
		// ['pt' => 304, 'es' => 464],
		// // Batch 7
		// ['pt' => 300, 'es' => 459],
		// ['pt' => 299, 'es' => 355],
		// ['pt' => 259, 'es' => 440],
		// ['pt' => 248, 'es' => 551],
		// ['pt' => 492],
		// ['pt' => 475],
		// ['pt' => 364],
		// ['pt' => 282],
		// ['pt' => 262],
		// ['pt' => 102, 'es' => 177],
		// ['pt' => 67, 'es' => 147],
		// ['pt' => 66],
		// ['pt' => 30, 'es' => 35],
		// ['pt' => 27],
		// ['pt' => 476, 'es' => 562],
		// ['pt' => 477],
		// ['pt' => 511, 'es' => 588],
		// ['pt' => 512],
		// ['pt' => 546, 'es' => 610],
		// ['pt' => 547],
		// ['pt' => 266, 'es' => 281],
		// ['pt' => 204, 'es' => 358],
		// ['pt' => 427, 'es' => 526],
		// ['pt' => 581],
		// ['pt' => 249],
		// ['pt' => 241],
		// ['pt' => 489],
		// ['pt' => 490],
		// ['pt' => 543, 'es' => 614],
		// ['pt' => 558, 'es' => 613],
		// ['pt' => 564, 'es' => 619],
		// ['pt' => 497],
		// ['pt' => 534],
		// ['pt' => 584],
		// ['pt' => 498],
		// ['pt' => 289],
		// ['pt' => 258],
		// ['pt' => 208],
		// ['pt' => 321],
		// ['pt' => 288],
		// ['pt' => 383],
		// ['pt' => 381]
	];

	if (!empty($collection)) {
		$collections = [$collection];
	}

	foreach ($collections as $collection) {
		$current_term = null;

		foreach ($collection as $language => $id) {
			try {
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

				if ($language === array_key_first($collection)) {
					$args = [
						'slug' => $data['slug'],
					];

					if ($data['parent'] !== 0) {
						$parent_data = json_decode(wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/collection/{$data['parent']}")['body'], true, JSON_UNESCAPED_SLASHES);
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
			} catch (\Throwable $error) {
				echo '</pre>';
				echo $error->getMessage();
				var_dump($language, $id);
				echo '</pre>';
				// die();
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

			if ($language === array_key_first($genre)) {
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
	$json_data = file_get_contents('http://localhost/content-f7p.json');
	$json_data = json_decode($json_data, true);
	foreach ($json_data as $json_data_key => $post_language) {
		foreach ($post_language as $value) {
			$language = $value['language'];
			$id = $value['id'];
			if (!empty($id)) {
				try {
					$response = wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/video/{$id}");
					$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);
					$title = $data['title']['rendered'];

					if (empty($title)) {
						$title = 'IMPORT ERROR - ' . $language . ' ' . $id;
					}

					foreach (['video_thumbnail', 'video_image_hover','image_content_header'] as $image_field) {
						$url = isset($data['acf'][$image_field]['url']) ? $data['acf'][$image_field]['url'] : '';
						if (!empty($url)) {
							$filename = $data['acf'][$image_field]['filename'];
							$file_id = get_attachment_id_by_name($filename) ?: upload_file_by_url($url);
							$data['acf'][$image_field] = $file_id;
						}
					}

					if ($language === 'pt') {
						$new_video = [
							'post_title' => $title,
							'post_type' => 'video',
							'post_status' => 'publish',
						];
						$video_id = wp_insert_post($new_video);

						$date_gmt = explode('T', $data['date_gmt']);

						wp_update_post([
							'ID' => $video_id,
							'post_date' => $date_gmt[0] . ' ' . $date_gmt[1],
						]);
					}

					if ($video_id) {
						$row = [
							'language' => $language,
							'title' => $title,
							'slug' => $data['slug'],
							...$data['acf'],
						];

						add_row('field_670ff24637fba', $row, $video_id);

						if (isset($data['taxonomies']['collection']) && is_array($data['taxonomies']['collection'])) {
							foreach ($data['taxonomies']['collection'] as $collection) {
								$slug = sanitize_title($collection['name']);
								$collection_term_exists = false;

								$collection_terms = get_terms([
									'taxonomy'   => 'collection',
									'hide_empty' => false,
								]);

								foreach ($collection_terms as $term) {
									if ($slug === $term->slug) {
										$collection_term_exists = true;
									}

									$languages = get_field('languages', $term);
									foreach ($languages as $collection_language) {
										if ($collection_language['language'] === $language && $slug === $collection_language['slug']) {
											$collection_term_exists = true;
										}
									}
								}

								if ($collection_term_exists === false) {
									var_dump('term created for ' . $language . ' ' . $id . ' ' . $collection['term_id']);
									import_collection_terms([$language => $collection['term_id']]);
								}
							}
						}

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
				} catch (\Throwable $error) {
					echo '<pre>';
					var_dump([
						'language' => $language,
						'id' => $id,
						'error' => $error->getMessage(),
					]);
					echo '</pre>';
				}
			}
		}
	}
}

// add_action('admin_init', 'import_genre_terms');
// add_action('admin_init', 'import_category_terms');

add_action('admin_menu', function () {
    add_management_page(
        'Import Content',
        'Import Content',
        'manage_options',
        'import-content',
        'import_content_page'
    );
});

function import_content_page() {
    if (isset($_POST['import_videos'])) {
        import_videos();
    }

    if (isset($_POST['clear_content'])) {
        clear_content();
    }

    if (isset($_POST['import_collection_terms'])) {
        import_collection_terms();
    }

	$options = [
		'import_collection_terms' => 'Import Collection Terms',
		'import_videos' => 'Import Videos',
		'clear_content' => 'Clear Content',
	];

	echo '<h2>Import Content</h2>';

	foreach ($options as $key => $value) {
		echo '
		<div class="wrap">
			<form method="post">
				<input type="submit" name="' . $key . '" class="button button-primary" value="' . $value . '">
			</form>
		</div>
		';
	}
}

function get_import_error_videos() {
	$args = [
		'post_type' => 'video',
		's' => 'IMPORT ERROR',
		'posts_per_page' => -1,
		'fields' => 'ids',
	];

	$posts = get_posts($args);
	echo 'Total de erros:' . count($posts);

	echo '<table>';
	echo '<tr>';
	echo '<td>id</td>';
	echo '<td>language</td>';
	echo '<td>url</td>';
	echo '<td>issue</td>';
	echo '<td>Admin link</td>';
	echo '</tr>';
	foreach (array_reverse($posts) as $post_id) {
		wp_delete_post($post_id, true);
		echo '<tr>';
		$data = explode(' ', get_the_title($post_id));
		$language = $data[3];
		$id = $data[4];
		echo '<td>' . $id . '</td>';
		echo '<td>' . $language . '</td>';
		$url = "https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/video/{$id}";
		echo '<td><a href="' . $url . '" target="blank">' . $url . '</a>';

		$response = wp_remote_get("https://v3.feliz7play.com/{$language}/e/wp-json/wp/v2/video/{$id}");
		$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);

		echo '<td>';
		if (isset($data['code'])) {
			echo $data['data']['status'] . ' - ' . $data['code'] . ' - ' . $data['message'];
		}

		else if (empty($data['title']['rendered'])) {
			echo 'Missing title';
		}
		echo '</td>';

		$admin_url = "https://v3.feliz7play.com/{$language}/wp-admin/post.php?post={$id}&action=edit";
		echo '<td><a href="' . $admin_url . '" target="blank">' . $admin_url . '</a>';

		echo '</tr>';
	}
	echo '</table>';
}

// get_import_error_videos();
// die();

// $posts = get_posts($args = [
// 	'post_type' => 'video',
// 	'posts_per_page' => -1,
// 	'fields' => 'ids',
// ]);
// $posts_with_multiple_languages = [];

// foreach ($posts as $post_id) {
// 	$languages = get_field('languages', $post_id);
// 	if (is_array($languages) && count($languages) > 2) {
// 		$posts_with_multiple_languages[] = $post_id;
// 	}
// }

// var_dump(count($posts_with_multiple_languages));
// die();

// echo '<pre>';
// $csv = array_map('str_getcsv', file('http://localhost/content-f7p.csv'));
// array_shift($csv);
// $data = [];
// foreach ($csv as $value) {
// 	array_push($data, [
// 		[
// 			'language' => $value[0],
// 			'id' => $value[1],
// 		],
// 		[
// 			'language' => $value[2],
// 			'id' => $value[3],
// 		],
// 	]);
// }
// $data = json_encode($data);
// var_dump($data);
// echo '</pre>';
// die();

// add_action('template_redirect', function() {
// 	// $posts = array_chunk(get_posts([
// 	// 	'post_type' => 'video',
// 	// 	'posts_per_page' => -1,
// 	// 	'fields' => 'ids',
// 	// ]), 500);

// 	// $posts = $posts[0]; ok
// 	// $posts = $posts[1]; ok
// 	// $posts = $posts[2]; ok
// 	// $posts = $posts[3]; ok
// 	// $posts = $posts[4]; ok
// 	// $posts = $posts[5]; ok
// 	// $posts = $posts[6]; ok
// 	// $posts = $posts[7]; ok

// 	$posts = get_posts([
// 		'post_type' => 'video',
// 		'posts_per_page' => -1,
// 		'fields' => 'ids',
// 	]);

// 	foreach ($posts as $post_id) {
// 		$local_response = wp_remote_get("http://localhost/wp-json/wp/v2/video/{$post_id}");
// 		$local_data = json_decode($local_response['body'], true, JSON_UNESCAPED_SLASHES);

// 		if (!isset($local_data['acf']['languages'])) {
// 			echo 'languages not found.';
// 		}

// 		echo '<div style="font-family:arial;font-size:12px;">';
// 		echo '<summary>' . $local_data['title']['rendered'] . ' - ' . $post_id .  '</summary>';

// 		$local_taxonomies = [];
// 		foreach ($local_data['taxonomies'] as $taxonomy => $terms) {
// 			foreach ($terms as $term) {
// 				foreach ($term['languages'] as $term_language) {
// 					$local_taxonomies[$taxonomy][] = $term_language['slug'];
// 				}
// 			}
// 		}
// 		// var_dump($local_taxonomies);

// 		foreach ($local_data['acf']['languages'] as $key => $language) {
// 			$old_id = get_post_meta($post_id, 'old_id_' . $key, true);
// 			if (empty($old_id)) {
// 				echo 'Old id not found.';
// 			}

// 			$response = wp_remote_get("https://v3.feliz7play.com/{$key}/e/wp-json/wp/v2/video/{$old_id}");
// 			$data = json_decode($response['body'], true, JSON_UNESCAPED_SLASHES);

// 			echo '<hr/>';
// 			echo  '<strong>' . $key . ' - ' . $old_id . '</strong>';

// 			$api_taxonomies = [];
// 			foreach ($data['taxonomies'] as $taxonomy => $terms) {
// 				foreach ($terms as $term) {
// 					$api_taxonomies[$taxonomy][] = $term['slug'];
// 				}
// 			}

// 			// var_dump($api_taxonomies);
// 			foreach ($api_taxonomies as $taxonomy => $terms) {
// 				echo '<p>';
// 				echo 'taxonomy ' . $taxonomy . ' <br/> ';
// 				foreach ($terms as $term) {
// 					if (in_array($term, $local_taxonomies[$taxonomy])) {
// 						echo '- ' . $term . ' ok.';
// 					} else {
// 						echo '<span style="display:block;background-color:red;color:#fff;">ERROR: ' . $term . ' not found.</span>';
// 					}
// 				}
// 				echo '</p>';
// 			}

// 			foreach ($local_data['acf']['languages'][$key] as $field_key => $value) {
// 				echo '<p>';

// 				if ($field_key === 'title') {
// 					// var_dump('<br/>' . $value . '<br/>' . $data['title']['rendered'] . '<br/>');
// 					if ($data['title']['rendered'] = $value) {
// 						echo 'title ok.';
// 					} else {
// 						echo '<span style="display:block;background-color:red;color:#fff;">ERROR: ' . $field_key . ' diff.</span>';
// 					}
// 				}

// 				else if ($field_key === 'slug') {
// 					if ($data['slug'] === $value) {
// 						echo 'slug ok.';
// 					} else {
// 						echo '<span style="display:block;background-color:red;color:#fff;">ERROR: ' . $field_key . ' diff.</span>';
// 					}
// 				}

// 				else if (in_array($field_key, ['video_image_hover', 'video_thumbnail', 'image_content_header'])) {
// 					$local_file = end(explode('/', $value));
// 					$api_file = end(explode('/', $data['acf'][$field_key]['url']));
// 					$api_file = str_replace('--', '-', $api_file);
// 					$api_file = str_replace('%', '', $api_file);


// 					if ($local_file === $api_file) {
// 						echo $field_key . ' ok.';
// 					} else {
// 						// Verifica se a imagem criada durante o upload termina com -1 no caso de ter sido duplicada
// 						$local_file_name = explode('.', $local_file)[0];
// 						if (str_ends_with($local_file_name, '-1')) {
// 							$local_file_name = substr($local_file_name, 0, -2);
// 							$local_file = $local_file_name . '.' . end(explode('.', $local_file));
// 						}

// 						if ($local_file === $api_file) {
// 							echo $field_key . ' ok.';
// 						} else {
// 							$api_url = $data['acf'][$field_key]['url'];
// 							$api_response = wp_remote_head($api_url);
// 							if (is_wp_error($api_response) || wp_remote_retrieve_response_code($api_response) == 404) {
// 								echo '<span style="display:block;background-color:red;color:#fff;">ERROR: API ' . $field_key . ' URL returns 404.</span>';
// 							} else {
// 								var_dump('LOCAL: ' . $local_file);
// 								var_dump('API: ' . $api_file);
// 								echo '<span style="display:block;background-color:red;color:#fff;">ERROR: ' . $field_key . ' diff.</span>';
// 							}
// 						}
// 					}
// 				}

// 				elseif ($data['acf'][$field_key] == $value) {
// 					echo $field_key . ' ok.';
// 				}

// 				echo '</p>';
// 			}
// 		}

// 		echo '</div><br/>';
// 	}

// 	die();
// });
