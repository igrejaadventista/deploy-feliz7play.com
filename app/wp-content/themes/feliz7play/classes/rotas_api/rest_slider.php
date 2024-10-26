<?php

add_action('rest_api_init', function () {
	register_rest_route('wp/v3', '/slider', [
		'methods' => 'GET',
		'callback' => function () {
			$sliders = [];
			$items = get_field('sliders', 'option');

			foreach ($items as $item) {
				$item = $item['slider_object'];
				$type = get_field('slider_type', $item);
				$source = get_field('slider_source', $item);
				$languages = get_field('slider_languages', $item);

				if (!empty($languages)) {
					$filtered_languages = [];
					foreach ($languages as $key => $language) {
						$languages[$language['language']] = array_diff_key($language, ['language' => '']);
						unset($languages[$key]);
					}
				}

				$images = [
					'desktop' => get_field('slider_desktop_image', $item)['url'],
					'tablet' => get_field('slider_tablet_image', $item)['url'],
					'mobile' => get_field('slider_mobile_image', $item)['url'],
				];

				if ($type === 'video') {
					if ($source === 'video') {
						$video = get_field('slider_video_object', $item);

						array_push($sliders, [
							'id' => $item->ID,
							'source' => $source,
							'languages' => $languages,
							'images' => $images,
							'video' => array_merge(get_post_infos($video), [
								'genre' => get_the_terms($video->ID, 'genre')[0]->name,
								'category' => get_the_terms($video->ID, 'category')[0],
							]),
						]);
					}

					if ($source === 'collection') {
						$collection = get_field('to_collection', $item);

						array_push($sliders, [
							'id' => $item->ID,
							'source' => $source,
							'languages' => $languages,
							'images' => $images,
							'collection' => get_collection_infos($collection),
						]);
					}
				}

				if ($type === 'custom') {
					array_push($sliders, [
						'id' => $item->ID,
						'type' => $type,
						'languages' => $languages,
						'images' =>	$images
					]);
				}
			}

			return new WP_REST_Response($sliders, 200);
		}
	]);
});
