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

		add_action('admin_menu', [$this, 'register_algolia_page']);
		add_action('wp_ajax_nopriv_index_data', [$this, 'index_data']);
        add_action('wp_ajax_index_data', [$this, 'index_data']);
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
		}

		require_once get_template_directory() . '/algolia.php';
	}

	function get_data_to_index() {
		$data = [];

		$videos = get_posts([
			'post_type' => 'video',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		]);

		$videos = array_filter($videos, function($video) {
			return !empty(get_field('languages', $video->ID));
		});

		foreach ($videos as $video) {
			$languages = get_field('languages', $video->ID);

			$collection = get_the_terms($video->ID, 'collection');
			if (is_array($collection)) {
				$collection = $collection[0];
				$collection->parent_slug = get_term($collection->parent, 'collection')->slug;
			}

			foreach ($languages as $language) {
				array_push($data, [
					'id' => $video->ID . '_' . $language['language'],
					'title' => $language['title'],
					'slug' => $language['slug'],
					'language' => $language['language'],
					'subtitle' => $language['post_subtitle'],
					'description' => $language['post_blurb'],
					'thumbnail' => $language['video_thumbnail']['url'],
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
						array_push($data, [
							'id' => $term->term_id . '_' . $language['language'],
							'title' => $language['title'],
							'slug' => $language['slug'],
							'language' => $language['language'],
							'subtitle' => isset($language['post_subtitle']) ? $language['post_subtitle'] : '',
							'description' => isset($language['description']) ? $language['description'] : '',
						]);
					}
				}
			}
		}

		return $data;
	}

	function index_data() {
		$items_to_index = self::get_data_to_index();

		foreach ($items_to_index as $item) {
			$item_id = $item['id'];
			unset($item['id']);

			$client = SearchClient::create(self::$app_id, self::$api_key_write);
			$client->addOrUpdateObject(self::$index, $item_id, $item);
		}
	}
}

$Algolia = new Algolia();
