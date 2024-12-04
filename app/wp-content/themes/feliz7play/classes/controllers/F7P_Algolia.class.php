<?php
use Algolia\AlgoliaSearch\Api\SearchClient;

class Algolia {
	static $index;
	static $app_id;
	static $api_key_search;
	static $api_key_write;

	public function __construct() {
		self::$index = get_option('algolia_index');
		self::$app_id = get_option('algolia_app_id');
		self::$api_key_search = get_option('algolia_api_key_search');
		self::$api_key_write = get_option('algolia_api_key_write');

		add_action( 'init', function() {
			add_action('admin_menu', [$this, 'register_algolia_page']);

			add_action('wp_ajax_nopriv_index_data', [$this, 'index_data']);
			add_action('wp_ajax_index_data', [$this, 'index_data']);

			add_action('wp_ajax_nopriv_get_data_to_index', [$this, 'get_data_to_index']);
			add_action('wp_ajax_get_data_to_index', [$this, 'get_data_to_index']);

			// if (!is_admin()) {
			// 	echo '<pre>';
			// 	var_dump(self::get_data_to_index());
			// 	echo '</pre>';
			// }
        }, 99);
	}

	function register_algolia_page() {
		add_menu_page(
			'Algolia',
			'Algolia',
			'manage_options',
			'algolia',
			[$this, 'algolia_page_content'],
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MDAgNTAwLjM0Ij48cGF0aCBmaWxsPSIjYTdhYWFkIiBkPSJNMjUwIDBDMTEzLjM4IDAgMiAxMTAuMTYuMDMgMjQ2LjMyYy0yIDEzOC4yOSAxMTAuMTkgMjUyLjg3IDI0OC40OSAyNTMuNjcgNDIuNzEuMjUgODMuODUtMTAuMiAxMjAuMzgtMzAuMDUgMy41Ni0xLjkzIDQuMTEtNi44MyAxLjA4LTkuNTJsLTIzLjM5LTIwLjc0Yy00Ljc1LTQuMjItMTEuNTItNS40MS0xNy4zNy0yLjkyLTI1LjUgMTAuODUtNTMuMjEgMTYuMzktODEuNzYgMTYuMDQtMTExLjc1LTEuMzctMjAyLjA0LTk0LjM1LTIwMC4yNi0yMDYuMUM0OC45NiAxMzYuMzcgMTM5LjI2IDQ3LjE1IDI1MCA0Ny4xNWgyMDIuODN2MzYwLjUzTDMzNy43NSAzMDUuNDNjLTMuNzItMy4zMS05LjQzLTIuNjYtMTIuNDMgMS4zMS0xOC40NyAyNC40Ni00OC41NiAzOS42Ny04MS45OCAzNy4zNi00Ni4zNi0zLjItODMuOTItNDAuNTItODcuNC04Ni44Ni00LjE1LTU1LjI4IDM5LjY1LTEwMS41OCA5NC4wNy0xMDEuNTggNDkuMjEgMCA4OS43NCAzNy44OCA5My45NyA4Ni4wMS4zOCA0LjI4IDIuMzEgOC4yOCA1LjUzIDExLjEzbDI5Ljk3IDI2LjU3YzMuNCAzLjAxIDguOCAxLjE3IDkuNjMtMy4zIDIuMTYtMTEuNTUgMi45Mi0yMy42IDIuMDctMzUuOTUtNC44My03MC4zOS02MS44NC0xMjcuMDEtMTMyLjI2LTEzMS4zNS04MC43My00Ljk4LTE0OC4yMyA1OC4xOC0xNTAuMzcgMTM3LjM1LTIuMDkgNzcuMTUgNjEuMTIgMTQzLjY2IDEzOC4yOCAxNDUuMzYgMzIuMjEuNzEgNjIuMDctOS40MiA4Ni4yLTI2Ljk3TDQ4My4zOSA0OTcuOGM2LjQ1IDUuNzEgMTYuNjIgMS4xNCAxNi42Mi03LjQ4VjkuNDlDNTAwIDQuMjUgNDk1Ljc1IDAgNDkwLjUxIDBIMjUwWiIvPjwvc3ZnPg0K',
		);
	}

	function algolia_page_content() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			update_option('algolia_index', $_POST['algolia_index']);
			update_option('algolia_app_id', $_POST['algolia_app_id']);
			update_option('algolia_api_key_search', $_POST['algolia_api_key_search']);
			update_option('algolia_api_key_write', $_POST['algolia_api_key_write']);
			update_option('algolia_api_batch', $_POST['algolia_api_batch']);
		}

		require_once get_template_directory() . '/algolia.php';
	}

	function get_terms_names($terms, $current_language) {
		$sorted_terms = [];

		if (!empty($terms)) {
			foreach ($terms as $term) {
				$term_language = get_field('languages', $term);
				foreach ($term_language as $lang) {
					if ($lang['language'] === $current_language) {
						array_push($sorted_terms, $lang['title']);
					}
				}
			}
		}

		return implode(', ', $sorted_terms);
	}

	function get_data_to_index() {
		$data = [];

		$videos = get_posts([
			'post_type' => 'video',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		]);

		foreach ($videos as $video) {
			$languages = get_field('languages', $video->ID);

			$collection = get_the_terms($video->ID, 'collection');
			if (is_array($collection) && !empty($collection)) {
				$collection[0]->parent_slug = get_term($collection->parent, 'collection')->slug;
			}

			foreach (['audio', 'subtitle'] as $value) {
				$terms = get_the_terms($video->ID, 'language_' . $value);
				if (!empty($terms)) {
					$terms = array_map(function($term) {
						return $term->name;
					}, $terms);
					$video->$value = implode(', ', $terms);
				}
			}

			foreach ($languages as $language) {
				$current_language = $language['language'];

				array_push($data, [
					'type' => 'video',
					'id' => $video->ID . '_' . $current_language,
					'title' => $language['title'],
					'slug' => $language['slug'],
					'language' => $current_language,
					'subtitle' => $language['post_subtitle'],
					'description' => $language['post_blurb'],
					'thumbnail' => $language['video_thumbnail']['url'],
					'genre' => self::get_terms_names(get_the_terms($video->ID, 'genre'), $current_language),
					'collection' => self::get_terms_names(get_the_terms($video->ID, 'collection'), $current_language),
					'audio' => $video->audio,
					'subtitles' => $video->subtitle,
					'link' => get_link_site_next($language['slug'], $language['post_video_type'], $collection),
				]);
			}
		}

		foreach (['genre', 'collection', 'category'] as $taxonomy) {
			$term_query = new WP_Term_Query([
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			]);

			$terms = $term_query->get_terms();

			foreach ($terms as $term) {
				$languages = get_field('languages', $term);
				if (is_array($languages)) {
					foreach ($languages as $language) {
						$current_language = $language['language'];

						$term_data = [
							'type' => $taxonomy,
							'id' => $term->term_id . '_' . $current_language,
							'title' => $language['title'],
							'slug' => $language['slug'],
							'language' => $current_language,
							'subtitle' => isset($language['post_subtitle']) ? $language['post_subtitle'] : '',
							'description' => isset($language['description']) ? $language['description'] : '',
						];

						if ($taxonomy === 'collection') {
							$term_data = array_merge($term_data, [
								'link' => get_link_site_next($language['slug'], 'Episode', $term),
								'genre' => self::get_terms_names([$language['collection_genre']], $current_language),
								'category' => self::get_terms_names($language['collection_category'], $current_language),
							]);
						}

						array_push($data, $term_data);
					}
				}
			}
		}

		wp_send_json($data);
		// return $data;
	}

	function index_data() {
		$item = $_POST['item'];
		$item_id = $item['id'];
		unset($item['id']);

		foreach ($item as $key => $value) {
			$item[$key] = stripslashes($value);
		}

		$client = SearchClient::create(self::$app_id, self::$api_key_write);
		$response = $client->addOrUpdateObject(self::$index, $item_id, $item);
		$get_edit_link_function = $item['type'] === 'video' ? 'get_edit_post_link' : 'get_edit_term_link';

		wp_send_json(array_merge($response, [
			'title' => $item['title'],
			'language' => $item['language'],
			'type' => $item['type'],
			'edit_link' => $get_edit_link_function(explode('_', $item_id)[0]),
		]));
	}
}

$Algolia = new Algolia();
