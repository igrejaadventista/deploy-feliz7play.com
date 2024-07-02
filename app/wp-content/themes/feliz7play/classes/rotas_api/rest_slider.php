<?php

add_action('rest_api_init', function () {
	register_rest_route('wp/v3', '/slider', array(
		'methods' => 'GET',
		'callback' => 'get_page_option',
	));
});


function get_extras($id, $field_name)
{
	$values = [];

	switch ($field_name) {
        case 'video':
			$items = get_field('post_extra', $id);
			foreach ($items as $item) {
				$list_videos = [];
				foreach($item['post_extra_list_videos'] as $video_item) { 
					$video_id = $video_item['post_extra_video']->ID;
					$meta = get_post_meta($video_id);
			
					$title =                $video_item['extra_video']->post_title;
					$slug =                 $video_item['extra_video']->post_name;
					$video_type =           $meta['post_video_type'][0];
					$video_episode =        $meta['video_episode'][0];
					$subtitle =             $meta['post_subtitle'][0];
					$description =          wp_strip_all_tags($meta['post_blurb'][0]);
					$video_host =           $meta['post_video_host'][0];
					$video_id =             $meta['post_video_id'][0];
			
					$post_download_link =   $meta['link_download_app'][0];
					$download =             $meta['download'][0];
					$post_year =            $meta['post_year'][0];
					$post_video_rating =    $meta['post_video_rating'][0];
					$post_video_age_rating =    $meta['post_video_age_rating'][0];
					$redes =                get_field('redes', $id);
					$production =           get_field('production', $id);
					$collection =           get_the_terms($id, 'collection')[0];
			
					if ($collection) {
						$collection->parent_slug = get_term($collection->parent, 'collection')->slug;
					}
			
					$genre =                get_the_terms($id, 'genre')[0];
					$category =             get_the_terms($id, 'category')[0];
					$video_lenght =         $meta['post_video_length'][0];
					$video_quality =        $meta['post_video_quality'][0];
			
					$video_thumbnail =      wp_get_attachment_image_src($meta['video_thumbnail'][0] == "" || is_null($meta['video_thumbnail'][0]) ? $meta['video_image_hover'][0] : $meta['video_thumbnail'][0])[0];
					$video_image_hover =    wp_get_attachment_image_src($meta['video_image_hover'][0])[0];
			
					$link = get_link_site_next($slug, $video_type, $collection);
			
					$video_values = array(
						'id' => $id,
						'title' => $title,
						'slug' => $slug,
						'video_type' => $video_type,
						'video_episode' => $video_episode,
						'subtitle' => $subtitle,
						'description' => $description,
						'genre' => $genre,
						'category' => $category,
						'collection' => $collection,
						'video_host' => $video_host,
						'video_id' => $video_id,
						'post_download_link' => $post_download_link,
						'download' => $download,
						'year' => $post_year,
						'video_rating' => $post_video_rating,
						'video_age_rating' => $post_video_age_rating,
						'video_thumbnail' => $video_thumbnail,
						'video_image_hover' => $video_image_hover,
						'post_video_length' => $video_lenght,
						'post_video_quality' => $video_quality,
						'redes' => $redes,
						'production' => $production,
						'link' => $link
					);
			
					array_push($list_videos, $video_values);
				}

				$values[] = array(
					'title' => $item['post_extra_title'],
					'videos' => $list_videos
				);
			}
			break;

		case 'collection':
			$items = get_field('extra', 'term_' . $id);
			foreach ($items as $item) {
				$list_videos = [];
				foreach($item['extra_list_videos'] as $video) { 
					$id = $video['extra_video']->ID;
					$meta = get_post_meta($id);
			
					$title =                $video['extra_video']->post_title;
					$slug =                 $video['extra_video']->post_name;
					$video_type =           $meta['post_video_type'][0];
					$video_episode =        $meta['video_episode'][0];
					$subtitle =             $meta['post_subtitle'][0];
					$description =          wp_strip_all_tags($meta['post_blurb'][0]);
					$video_host =           $meta['post_video_host'][0];
					$video_id =             $meta['post_video_id'][0];
			
					$post_download_link =   $meta['link_download_app'][0];
					$download =             $meta['download'][0];
					$post_year =            $meta['post_year'][0];
					$post_video_rating =    $meta['post_video_rating'][0];
					$post_video_age_rating =    $meta['post_video_age_rating'][0];
					$redes =                get_field('redes', $id);
					$production =           get_field('production', $id);
					$collection =           get_the_terms($id, 'collection')[0];
			
					if ($collection) {
						$collection->parent_slug = get_term($collection->parent, 'collection')->slug;
					}
			
					$genre =                get_the_terms($id, 'genre')[0];
					$category =             get_the_terms($id, 'category')[0];
					$video_lenght =         $meta['post_video_length'][0];
					$video_quality =        $meta['post_video_quality'][0];
			
					$video_thumbnail =      wp_get_attachment_image_src($meta['video_thumbnail'][0] == "" || is_null($meta['video_thumbnail'][0]) ? $meta['video_image_hover'][0] : $meta['video_thumbnail'][0])[0];
					$video_image_hover =    wp_get_attachment_image_src($meta['video_image_hover'][0])[0];
			
					$link =                 get_link_site_next($slug, $video_type, $collection);
			
					$video_values = array(
						'id' => $id,
						'title' => $title,
						'slug' => $slug,
						'video_type' => $video_type,
						'video_episode' => $video_episode,
						'subtitle' => $subtitle,
						'description' => $description,
						'genre' => $genre,
						'category' => $category,
						'collection' => $collection,
						'video_host' => $video_host,
						'video_id' => $video_id,
						'post_download_link' => $post_download_link,
						'download' => $download,
						'year' => $post_year,
						'video_rating' => $post_video_rating,
						'video_age_rating' => $post_video_age_rating,
						'video_thumbnail' => $video_thumbnail,
						'video_image_hover' => $video_image_hover,
						'post_video_length' => $video_lenght,
						'post_video_quality' => $video_quality,
						'redes' => $redes,
						'production' => $production,
						'link' => $link
					);
			
					array_push($list_videos, $video_values);
				}

				$values[] = array(
					'title' => $item['extra_title'],
					'videos' => $list_videos
				);
			}
		break;
    }
	return $values;
}

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
					$meta = get_post_meta($target);

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
					$category = get_the_terms($target, 'category')[0];

					$video_host =           get_field('post_video_host', $target);
					$video_id =             get_field('post_video_id', $target);
					$video_thumbnail =      wp_get_attachment_image_src($meta['video_thumbnail'][0] == "" || is_null($meta['video_thumbnail'][0]) ? $meta['video_image_hover'][0] : $meta['video_thumbnail'][0])[0];
					$extras = get_extras($target, 'video');

				} else {
					$meta = get_term_meta($target);

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
					$category = get_field('collection_category', 'term_' . $target);
					$collection_father = get_field('to_collection', $item->ID)->parent ? get_term(get_field('to_collection', $item->ID)->parent)->slug : false;
					$video_host =  get_field('collection_video_host', 'term_' . $target);
					$video_id =    get_field('collection_video_id', 'term_' . $target);
					$video_thumbnail = wp_get_attachment_image_src($meta['collection_image'][0])[0];
					$extras = get_extras($target, 'collection');
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
					'video_thumbnail' => $video_thumbnail,
					'genre' => $genre,
					'category' => $category,
					'season' => $season,
					'collection_father' => $collection_father,

					'slider_desktop' =>	$slider_desktop,
					'slider_tablet'	=> $slider_tablet,
					'slider_mobile' => $slider_mobile,
					'extras' => $extras
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
