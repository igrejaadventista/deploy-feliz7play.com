<?php

add_action('rest_api_init', function () {
	register_rest_route('wp/v3', '/slider', array(
		'methods' => 'GET',
		'callback' => 'get_page_option',
	));
});

function get_page_option($data)
{

	$sliders = array();

	$itens = get_field('sliders', 'option');

	foreach ($itens as $item) {

		$item = $item['slider_object'];

		$type = get_field('slider_type', $item->ID);

		switch ($type) {
			case 'video':
				$title = $item->post_title;
				$source = get_field('slider_source', $item->ID);
				$logo = get_field('slider_logo', $item->ID)['url'];
				$slider_desktop = get_field('slider_desktop_image', $item->ID)['url'];
				$slider_tablet = get_field('slider_tablet_image', $item->ID)['url'];
				$slider_mobile = get_field('slider_mobile_image', $item->ID)['url'];

				if ($source == 'video') {

					$target = get_field('slider_video_object', $item->ID)->ID;
					$description = get_field('post_blurb',  $target);
					$slug = get_post_field('post_name', get_field('slider_video_object', $item->ID));
					$post_video_lenght = get_field('post_video_lenght', $target);
					$video_quality = get_field('post_video_quality', $target);
					$video_age_rating = get_field('post_video_age_rating', $target);
					$season = false;
					$video_year = get_field('post_year', $target);
					$rating = get_field('Rating', $target);
					$genre = get_the_terms($target, 'genre')[0]->name;

					$video_host =           get_field('post_video_host', $target);
					$video_id =             get_field('post_video_id', $target);

				} else {

					$target = get_field('to_collection', $item->ID)->term_id;
					$description = term_description($target);
					$slug = get_field('to_collection', $item->ID)->slug;
					$post_video_lenght = false;
					$video_quality = get_field('collection_video_quality', 'term_' . $target);
					$video_age_rating = get_field('collection_video_age_rating', 'term_' . $target);
					$season = '1 temporada';
					$video_year = get_field('year', 'term_' . $target);
					$rating = get_field('Rating', 'term_' . $target);
					$genre = get_field('collection_genre', 'term_' . $target)->name;
					$collection_father = get_field('to_collection', $item->ID)->parent ? get_term(get_field('to_collection', $item->ID)->parent)->slug : false;
					$video_host =  get_field('collection_video_host', 'term_' . $target);
					$video_id =    get_field('collection_video_id', 'term_' . $target);
				}



				$slider = array(
					'id' => $item->ID,
					'title' => $title,
					'slug' => $slug,
					'type' => $type,
					'source' => $source,
					'target' => $target,
					'logo' => $logo,
					'description' => $description,
					'video_lenght' => $post_video_lenght,
					'rating' => $rating,
					'video_quality' => $video_quality,
					'video_age_rating' => $video_age_rating,
					'video_year' => $video_year,
					'video_host' => $video_host,
					'video_id' => $video_id,
					'genre' => $genre,
					'season' => $season,
					'collection_father' => $collection_father,

					'slider_desktop' =>	$slider_desktop,
					'slider_tablet'	=> $slider_tablet,
					'slider_mobile' => $slider_mobile

				);

				array_push($sliders, $slider);
				break;

			case 'custom':

				$title = $item->post_title;
				$source = get_field('to_collection', $item->ID);
				$description = get_field('slider_description', $item->ID);
				$slider_mobile = get_field('slider_button', $item->ID);
				$slider_text_button = get_field('slider_button', $item->ID);
				$slider_link_button = get_field('slider_button_link', $item->ID);
				$slider_desktop = get_field('slider_desktop_image', $item->ID)['url'];
				$slider_tablet = get_field('slider_tablet_image', $item->ID)['url'];
				$slider_mobile = get_field('slider_mobile_image', $item->ID)['url'];

				$slider = array(
					'id' => $item->ID,
					'title' => $title,
					'type' => $type,
					'description' => $description,
					'text_button' => $slider_text_button,
					'link_button' => $slider_link_button,
					'slider_desktop' =>	$slider_desktop,
					'slider_tablet'	=> $slider_tablet,
					'slider_mobile' => $slider_mobile

				);

				array_push($sliders, $slider);
				break;
		}
	}
	return new WP_REST_Response($sliders, 200);
}
