<?php
class Deepl {
	public function __construct() {
		add_action('init', function() {
			$authKey = '337ca2f4-8e2b-41f2-ba1c-51382743985c:fx'; // Replace with your key
			$deeplClient = new \DeepL\DeepLClient($authKey);

			$result = $deeplClient->translateText('Hello, world!', null, 'fr');
			echo $result->text;

			// add_action('acf/save_post', function($post_id) {
			// 	if (get_post_type($post_id) !== 'video' || self::$auto_index !== 'on') {
			// 		return;
			// 	}

			// 	$data_to_index = self::get_video_data($post_id);
			// 	foreach ($data_to_index as $item) {
			// 		self::index_data($item);
			// 	}
			// }, 10, 3);
        }, 99);
	}
}

$Deepl = new Deepl();
