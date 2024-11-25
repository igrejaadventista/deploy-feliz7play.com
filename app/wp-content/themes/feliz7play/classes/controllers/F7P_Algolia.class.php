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
			[$this, 'algolia_page_content']
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

	function index_data() {
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
			foreach ($languages as $language) {
				$client = SearchClient::create(self::$app_id, self::$api_key_write);
				$client->addOrUpdateObject(
					self::$index,
					$video->ID . '_' . $language['language'],
					[
						'title' => $language['title'],
						'slug' => $language['slug'],
						'language' => $language['language'],
						'subtitle' => $language['post_subtitle'],
						'description' => $language['post_blurb'],
						'thumbnail' => $language['video_thumbnail']['url'],
					],
				);
			}
		}
	}
}

$Algolia = new Algolia();
