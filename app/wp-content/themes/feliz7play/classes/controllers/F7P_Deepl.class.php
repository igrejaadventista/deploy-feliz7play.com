<?php
class Deepl {
	static $auth_key;

	public function __construct() {
		self::$auth_key = get_option('deepl_auth_key');

		add_action('admin_menu', [$this, 'register_algolia_page']);
		add_action('acf/save_post', function($post_id) {
			self::translate_video($post_id);
		}, 10, 3);
	}

	function register_algolia_page() {
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
			update_option('deepl_auth_key', $_POST['deepl_auth_key']);
		}

		require_once get_template_directory() . '/deepl.php';
	}

	public function translate_video($post_id) {
		if (get_post_type($post_id) !== 'video') {
			return;
		}

		$languages = get_field('languages', $post_id);
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

		$languages_to_translate = array_diff(['pt', 'es', 'en'], $current_post_languages);

		if (empty($default_language) || empty($languages_to_translate)) {
			return;
		}

		$deeplClient = new \DeepL\DeepLClient(self::$auth_key);

		foreach ($languages_to_translate as $language_to_translate) {
			$string_to_translate = $default_language['title'] . ' : ' . $default_language['post_subtitle'] . ' : ' . $default_language['post_blurb'];

			$translation = $deeplClient->translateText(
				$string_to_translate,
				null,
				$language_to_translate === 'en' ? 'en-us' : $language_to_translate
			);

			$translation = explode(': ', $translation->text);

			$row = $default_language;
			$row['language'] = $language_to_translate;
			$row['title'] = $translation[0];
			$row['post_subtitle'] = $translation[1];
			$row['slug'] = sanitize_title($translation[0]);
			$row['post_blurb'] = $translation[2];

			add_row('field_670ff24637fba', $row, $post_id);
			wp_set_post_terms($post_id, $language_to_translate, 'language_audio', true);
		}
	}
}

$Deepl = new Deepl();
