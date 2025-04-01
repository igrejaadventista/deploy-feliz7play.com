<?php
class Deepl {
	static $auth_key;
	static $deepl_client;
	static $auto_translate;
	static $required_languages = ['pt', 'es', 'en'];

	public function __construct() {
		add_action('admin_menu', [$this, 'register_deepl_page']);

		self::$auth_key = get_option('deepl_auth_key');
		if (!self::$auth_key) {
			return;
		}

		self::$deepl_client = new \DeepL\DeepLClient(self::$auth_key);

		self::$auto_translate = get_option('deepl_auto_translate');
		if (self::$auto_translate !== 'on') {
			return;
		}

		add_action('acf/save_post', function($post_id) {
			if (get_post_type($post_id) === 'video') {
				self::translate_video($post_id);
			}

			$post_id = str_replace('term_', '', $post_id);
			$term = get_term($post_id);
			if ($term !== null) {
				self::translate_term($term);
			}
		}, 10, 3);
	}

	function register_deepl_page() {
		add_menu_page(
			'Deepl',
			'Deepl',
			'manage_options',
			'deepl',
			[$this, 'deepl_page_content'],
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSI+PGcgY2xpcC1wYXRoPSJ1cmwoI2EpIj48cGF0aCBmaWxsPSIjZmZmIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Im0xMi4zNjcuMTc2IDguMjE3IDQuODI2Yy40MjUuMjQ3LjY1Mi42OC42NTIgMS4xNzR2OS41MjFjMCAuNDk0LS4yMjcuOTI4LS42NTIgMS4xNzRsLTUuMjE3IDNjLS4xOC4xMjgtLjI2MS40MjUtLjI2MS42NTN2My41MjFjLTMuNDYzLTIuMDEtNi45My00LjAxNS0xMC4zOTctNi4wMkwyLjcxNSAxNi44N2MtLjQyNS0uMjQ2LS42NTItLjY4LS42NTItMS4xNzNWNi4xNzVjMC0uNDguMjI3LS45MjcuNjUyLTEuMTc0TDEwLjkzMi4xNzZhMS40ODMgMS40ODMgMCAwIDEgMS40MzUgMFptLTEuNjIyIDcuNjMyYTEuMzA0IDEuMzA0IDAgMSAwLS4zNDIuNTU2bDMuNTg2IDIuMmExLjMwNSAxLjMwNSAwIDEgMCAuMzQxLS41NTVsLTMuNTg1LTIuMlptLTEuMjQ4IDcuOTY1YTEuMzA0IDEuMzA0IDAgMCAwIDEuMjQ4LTEuNjg3bDMuMzIyLTIuMDM5LS42MjMtLjM4My0zLjA0IDEuODY2YTEuMzA0IDEuMzA0IDAgMSAwLS45MDYgMi4yNDNaIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiLz48L2c+PGRlZnM+PGNsaXBQYXRoIGlkPSJhIj48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMCAwaDI0djI0SDB6Ii8+PC9jbGlwUGF0aD48L2RlZnM+PC9zdmc+DQo=',
		);
	}

	function deepl_page_content() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$deepl_auth_key = isset($_POST['deepl_auth_key']) ? sanitize_text_field($_POST['deepl_auth_key']) : null;
			update_option('deepl_auth_key', $deepl_auth_key, true);
			self::$auth_key = $deepl_auth_key;
			self::$deepl_client = new \DeepL\DeepLClient($deepl_auth_key);

			$deepl_auto_translate = isset($_POST['deepl_auto_translate']) ? sanitize_text_field($_POST['deepl_auto_translate']) : false;
			update_option('deepl_auto_translate', $deepl_auto_translate, true);
			self::$auto_translate = $deepl_auto_translate;
		}

		require_once get_template_directory() . '/deepl.php';
	}

	function get_content_languages($id) {
		$languages = get_field('languages', $id);
		if (!$languages) {
			return;
		}

		$default_language = '';
		$current_post_languages = [];
		foreach ($languages as $language_data) {
			if ($language_data['language'] === 'pt') {
				$default_language = $language_data;
			}
			$current_post_languages[] = $language_data['language'];
		}

		$languages_to_translate = array_diff(self::$required_languages, $current_post_languages);

		if (empty($default_language) || empty($languages_to_translate)) {
			return;
		}

		return [
			'default_language' => $default_language,
			'languages_to_translate' => $languages_to_translate,
		];
	}

	function get_translation($language, $data) {
		$translation = self::$deepl_client->translateText(
			$data,
			null,
			$language === 'en' ? 'en-us' : $language
		);

		if (is_array($translation)) {
			return array_map(function($item) {
				return $item->text;
			}, $translation);
		}

		return $translation->text;
	}

	public function translate_video($post_id) {
		$languages = self::get_content_languages($post_id);
		if (!$languages) {
			return;
		}

		foreach ($languages['languages_to_translate'] as $language_to_translate) {
			$translations = self::get_translation($language_to_translate, [
				$languages['default_language']['title'] ?? '',
				$languages['default_language']['post_subtitle'] ?? '',
				$languages['default_language']['post_blurb'] ?? '',
			]);

			$row = $languages['default_language'];
			$row['language'] = $language_to_translate;
			$row['title'] = $translations[0] ?? '';
			$row['post_subtitle'] = $translations[1] ?? '';
			$row['post_blurb'] = $translations[2] ?? '';
			$row['slug'] = sanitize_title($translations[0] ?? '');

			add_row('field_670ff24637fba', $row, $post_id);
			wp_set_post_terms($post_id, $language_to_translate, 'language_audio', true);
		}
	}

	public function translate_term($term) {
		$languages = self::get_content_languages($term);
		if (!$languages) {
			return;
		}

		$field_keys = [
			'genre' => 'field_6706bd52aa917',
			'category' => 'field_671244c1d177f',
			'collection' => 'field_6712409cbf2d8',
		];

		foreach ($languages['languages_to_translate'] as $language_to_translate) {
			$translations = self::get_translation($language_to_translate, [
				$languages['default_language']['title'] ?? '',
				$languages['default_language']['subtitle'] ?? '',
				$languages['default_language']['description'] ?? '',
				$languages['default_language']['collection_season_label'] ?? '',
			]);

			$row = $languages['default_language'];
			$row['language'] = $language_to_translate;
			$row['title'] = $translations[0] ?? '';
			$row['subtitle'] = $translations[1] ?? '';
			$row['description'] = $translations[2] ?? '';
			$row['slug'] = sanitize_title($translations[0] ?? '');
			$row['collection_season_label'] = $translations[3] ?? '';

			add_row($field_keys[$term->taxonomy], $row, $term);
		}
	}
}

$Deepl = new Deepl();
