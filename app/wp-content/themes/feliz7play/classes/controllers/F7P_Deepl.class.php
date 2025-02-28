<?php
class Deepl {
	public function __construct() {
		add_action('init', function() {
			add_action('acf/save_post', function($post_id) {
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

				$authKey = '';
				$deeplClient = new \DeepL\DeepLClient($authKey);

				foreach ($languages_to_translate as $language_to_translate) {
					$string_to_translate = $default_language['title'] . ' : ' . $default_language['post_subtitle'] . ' : ' . $default_language['slug'] . ' : ' . $default_language['post_blurb'];

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
					$row['slug'] = $translation[2];
					$row['post_blurb'] = $translation[3];

					add_row('field_670ff24637fba', $row, $post_id);
				}
			}, 10, 3);
        }, 99);
	}
}

$Deepl = new Deepl();
