<?php

function get_genre($genre_items)
{

    foreach ($genre_items as $g_item) {
        $genre_item = $g_item;
    }
    $image = get_field('image', 'term_' . $genre_item->term_id)['url'];

    $line_name = get_sub_field('genre_title');
    $category_items = get_sub_field('genre_category');

    $category_array = [];
    foreach ($category_items as $category_item) {
        $item = get_category_by_line($category_item);
       array_push($category_array, $item);     
    }

    $line = array(
        'id' => $genre_item->term_id, 
        'line_name' => $line_name, 
        'line_slug' => $genre_item->slug,  
        'source' => $genre_item->taxonomy, 
        'image_default' => $image,
        'categories' => $category_array
    );

    global $lines;
    array_push($lines, $line);
    return;
}

function get_genre_v2($genres_items)
{
    $genre_array = [];
    $category_array = [];
    $line_name = get_sub_field('genre_title');
    $category_items = get_sub_field('genre_category');

    foreach ($genres_items as $genre_item) {
        $image = get_field('image', 'term_' . $genre_item->term_id)['url'];
        $item = get_genre_by_line($genre_item, $image);
        array_push($genre_array, $item); 
    }

    foreach ($category_items as $category_item) {
        $item = get_category_by_line($category_item);
       array_push($category_array, $item);     
    }

    $line = array(
        'id' => '0', 
        'line_name' => $line_name, 
        'source' => 'genre', 
        'genres' => $genre_array,
        'categories' => $category_array
    );

    global $lines;
    array_push($lines, $line);
    return;
}

function get_genre_by_line($item, $image)
{
    $line = array(
        'id' => $item->term_id, 
        'line_name' => $item->name, 
        'line_slug' => $item->slug,  
        'source' => $item->taxonomy, 
        'image_default' => $image,
    );

    return $line;
}

function get_category_by_line($item)
{
    $line = array(
        'id' => $item->term_id, 
        'line_name' => $item->name, 
        'line_slug' => $item->slug,  
        'source' => $item->taxonomy, 
    );

    return $line;
}

function get_line_post($args, $limited = false)
{

    $items = array();
    $controle = array();

    $posts = get_posts($args);
    foreach ($posts as $post) {

        $id = $post->ID;

        $meta = get_post_meta($id);

        $title =                $post->post_title;
        $slug =                 $post->post_name;
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
        $extras =               get_field('post_extra', $id);
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

        $values = array(
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
            'extras' => $extras,
            'link' => $link
        );

        if ($limited) {

            $id_check = ($video_type == 'Single') ? $id : $collection->term_id;

            if (!in_array($id_check, $controle)) {
                array_push($controle, $id_check);
                array_push($items, $values);

                if ($limited == count($controle)) {
                    break;
                }
            }
        } else {
            array_push($items, $values);
        }
    }

    return $items;
}

function get_line_post_genre($args, $limited = false)
{

    $items = array();
    $controle = array();

    $posts = get_posts($args);
    foreach ($posts as $post) {

        $id = $post->ID;
        $meta = get_post_meta($id);

        $video_type = $meta['post_video_type'][0];



        $collection = return_parent_collection(get_the_terms($id, 'collection')[0]);

        if ($video_type == 'Single') {
            $id_check =  $id;
        } else {

            $meta = get_term_meta($collection->term_id);

            if ($meta['collection_enable'][0]) {
                $id_check =  $collection->term_id;
            } else {
                continue;
            }
        }

        if (!in_array($id_check, $controle)) {

            array_push($controle, $id_check);

            $values = ($video_type == 'Single') ? get_post_infos($post) : get_collection_infos($collection);

            array_push($items, $values);

            if ($limited > 0) {
                if ($limited == count($controle)) {
                    break;
                }
            }
        }
    }

    return $items;
}

function return_parent_collection($collection)
{

    if ($collection->parent) {
        return get_term($collection->parent, 'collection');
    }

    return $collection;
}

function get_collection_infos($collection)
{


    $id = $collection->term_id;
    $meta = get_term_meta($id);

    return array(
        'id' => $id,
        'title' => $collection->name,
        'slug' => $collection->slug,
        'video_type' => $collection->taxonomy,
        'video_thumbnail' => wp_get_attachment_image_src($meta['collection_image'][0])[0],
        'video_image_hover' => false
    );
}

function get_post_infos($post)
{

    $genre = get_the_terms($post->ID, 'genre')[0];
    $collection = get_the_terms($post->ID, 'collection')[0];
    if ($collection) {
        $collection->parent_slug = get_term($collection->parent, 'collection')->slug;
    }

    $languages = get_field('languages', $post->ID);

    if (!empty($languages)) {
        foreach ($languages as $language) {
            $key = $language['language'];
            $filtered_languages[$key] = array_diff_key($language, ['language' => '']);
            $filtered_languages[$key] = array_merge($filtered_languages[$key], [
                'video_type' => $language['post_video_type'],
                'subtitle' => $language['post_subtitle'],
                'description' => wp_strip_all_tags($language['post_blurb']),
                'video_host' => $language['post_video_host'],
                'video_id' => $language['post_video_id'],
                'video_thumbnail' => wp_get_attachment_image_src($language['video_thumbnail'][0] == "" || is_null($language['video_thumbnail'][0]) ? $language['video_image_hover'][0] : $language['video_thumbnail'])[0],
                'video_image_hover' => wp_get_attachment_image_src($language['video_image_hover'][0])[0],
                'link' => get_link_site_next($language['slug'], $language['post_video_type'], $collection)
            ]);

            foreach (['post_video_type', 'post_subtitle', 'post_blurb', 'post_video_host', 'post_video_id'] as $value) {
                unset($filtered_languages[$key][$value]);
            }
        }
    }

    return [
        'id' => $post->ID,
        'languages' => !empty($languages) ? $filtered_languages : 'Video languages not found.'
    ];
}


function get_line_collection($args)
{
    $items = array('included' => [], 'exclude' => []);

    $collections = get_terms($args);

    foreach ($collections as $collection) {

        $id = $collection->term_id;

        $meta = get_term_meta($id);

        $title = $collection->name;
        $slug = $collection->slug;
        $description = $collection->description;
        $genres = get_field('collection_genre', 'term_' . $id);
        $video_type = $collection->taxonomy;
        $video_thumbnail = wp_get_attachment_image_src($meta['collection_image'][0])[0];
        $video_image_hover = false;

        $values = array(
            'id' => $id, 
            'title' => $title, 
            'slug' => $slug, 
            'description' => $description,
            'genre' => $genres,
            'video_type' => $video_type, 
            'video_thumbnail' => $video_thumbnail, 
            'video_image_hover' => $video_image_hover
        );

        array_push($items['included'], $values);
    }
    return $items;
}


function get_collection($item)
{

    $line = array(
        'id' => $item->term_id, 
        'line_name' => $item->name, 
        'line_slug' => $item->slug,  
        'source' => $item->taxonomy, 
        'seasons' => get_field('seasons', 'collection_' . $item->term_id)
    );

    global $lines;
    array_push($lines, $line);

    return;
}


function get_custom($items)
{

    $line_name = get_sub_field('custom_title');
    $line_model = get_sub_field('model');
    // $limited = get_sub_field('n_itens');
    $limited_per_item = 1;

    $line = array(
        'id' => 0, 
        'line_name' => $line_name, 
        'line_slug' => false,  
        'source' => 'custom', 
        'model' => $line_model,  
        'items' => []
    );

    foreach ($items as $item) {

        switch ($item['acf_fc_layout']) {
            case 'collection':
                $args = array(
                    'taxonomy' => 'collection',  
                    'number' => 0, 
                    'include' => $item['to_custom_collection']->term_id
                );
                $collection = get_line_collection($args);

                if ($line_model == 'circle') {
                    $collection['included'][0]['video_thumbnail_circle'] = $item['image']['url'];
                }

                if ($line_model == 'vertical' || $line_model == 'highlight') {
                    $collection['included'][0]['video_thumbnail_vertical'] = $item['image']['url'];
                }

                array_push($line['items'], ...$collection['included']);
                break;

            case 'video':
                $args = array(
                    'post_type' => 'video', 
                    'fields' => '', 
                    'include' => $item['to_video']->ID, 
                    'numberposts' => 0
                );
                $post = get_line_post($args);

                if ($line_model == 'circle') {
                    $post[0]['video_thumbnail_circle'] = $item['image']['url'];
                }

                if ($line_model == 'vertical' || $line_model == 'highlight') {
                    $post[0]['video_thumbnail_vertical'] = $item['image']['url'];
                }

                array_push($line['items'], $post[0]);
                break;

                
            case 'slider':
                $type = get_field('slider_type', $item['to_slider']->ID);
                
                switch ($type) {
                    case 'video':
                        $title = $item->post_title;
                        $source = get_field('slider_source', $item['to_slider']->ID);
                        $logo = get_field('slider_logo', $item['to_slider']->ID)['url'];
                        $slider_desktop = get_field('slider_desktop_image', $item['to_slider']->ID)['url'];
                        $slider_tablet = get_field('slider_tablet_image', $item['to_slider']->ID)['url'];
                        $slider_mobile = get_field('slider_mobile_image', $item['to_slider']->ID)['url'];

                        if ($source == 'video') {
                            $meta = get_post_meta($target);

                            $target = get_field('slider_video_object', $item['to_slider']->ID)->ID;
                            $description = get_field('post_blurb',  $target);
                            $slug = get_post_field('post_name', get_field('slider_video_object', $item['to_slider']->ID));
                            $post_video_lenght = get_field('post_video_lenght', $target);
                            $video_hls_link = get_field('post_hls_link', $target);
                            $video_quality = get_field('post_video_quality', $target);
                            $video_age_rating = get_field('post_video_age_rating', $target);
                            $season = false;
                            $video_year = get_field('post_year', $target);
                            $rating = get_field('Rating', $target);
                            $genre = get_the_terms($target, 'genre')[0]->name;
                            $category = get_the_terms($target, 'category')[0];

                            $video_host = get_field('post_video_host', $target);
                            $video_id = get_field('post_video_id', $target);
                            $video_thumbnail = get_field('video_thumbnail', $target);
                            $extras = get_extras($target, 'video');

                        } else {

                            $meta = get_term_meta($target);

                            $target = get_field('to_collection', $item['to_slider']->ID)->term_id;
                            $description = term_description($target);
                            $slug = get_field('to_collection', $item['to_slider']->ID)->slug;
                            $post_video_lenght = false;
                            $video_quality = get_field('collection_video_quality', 'term_' . $target);
                            $video_age_rating = get_field('collection_video_age_rating', 'term_' . $target);
                            $season = '1 temporada';
                            $video_year = get_field('year', 'term_' . $target);
                            $rating = get_field('Rating', 'term_' . $target);
                            $genre = get_field('collection_genre', 'term_' . $target)->name;
                            $category = get_field('collection_category', 'term_' . $target);
                            $collection_father = get_field('to_collection', $item['to_slider']->ID)->parent ? get_term(get_field('to_collection', $item['to_slider']->ID)->parent)->slug : false;
                            $video_hls_link =  get_field('collection_hls_link', 'term_' . $target);
                            $video_host =  get_field('collection_video_host', 'term_' . $target);
                            $video_id =    get_field('collection_video_id', 'term_' . $target);
                            $video_thumbnail = get_field('collection_image', 'term_' . $target);
                            $extras = get_extras($target, 'collection');
                        }

                        $slider = array(
                            'id' => $item['to_slider']->ID,
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
                            'video_hls_link' => $video_hls_link,
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

                        // array_push($sliders, $slider);
                        break;

                    case 'custom':

                        $title = $item->post_title;
                        $source = get_field('to_collection', $item['to_slider']->ID);
                        $description = get_field('slider_description', $item['to_slider']->ID);
                        $slider_mobile = get_field('slider_button', $item['to_slider']->ID);
                        $slider_text_button = get_field('slider_button', $item['to_slider']->ID);
                        $slider_link_button = get_field('slider_button_link', $item['to_slider']->ID);
                        $slider_desktop = get_field('slider_desktop_image', $item['to_slider']->ID)['url'];
                        $slider_tablet = get_field('slider_tablet_image', $item['to_slider']->ID)['url'];
                        $slider_mobile = get_field('slider_mobile_image', $item['to_slider']->ID)['url'];

                        $slider = array(
                            'id' => $item['to_slider']->ID,
                            'title' => $title,
                            'type' => $type,
                            'description' => $description,
                            'text_button' => $slider_text_button,
                            'link_button' => $slider_link_button,
                            'slider_desktop' =>	$slider_desktop,
                            'slider_tablet'	=> $slider_tablet,
                            'slider_mobile' => $slider_mobile

                        );

                        // array_push($sliders, $slider);
                        break;
                }

                array_push($line['items'], $slider);
                break;
        }
    }

    global $lines;
    array_push($lines, $line);

    return;
}

function get_recentes()
{

    $line_name = get_sub_field('recentes_title');
    $limited = get_sub_field('n_itens');

    $line = array(
        'id' => 0, 
        'line_name' => $line_name, 
        'line_slug' => false,  
        'source' => 'custom', 
        'model' => 'default',  
        'items' => []
    );

    $args = array(
        'post_type'         => 'video',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
    );

    $controle = array();

    $posts = get_posts($args);
    foreach ($posts as $post) {

        $id = $post->ID;
        $meta = get_post_meta($id);

        $video_type = $meta['post_video_type'][0];

        $collection = return_parent_collection(get_the_terms($id, 'collection')[0]);
        $id_check = ($video_type == 'Single') ? $id : $collection->term_id;

        if (!in_array($id_check, $controle)) {

            array_push($controle, $id_check);

            $values = ($video_type == 'Single') ? get_post_infos($post) : get_collection_infos($collection);

            array_push($line['items'], $values);

            if ($limited > 0) {
                if ($limited == count($controle)) {
                    break;
                }
            }
        }
    }


    global $lines;
    array_push($lines, $line);

    return;
}

function pagination_array($items = array(), $page, $per_page)
{

    $page = is_null($page) ? 1 : $page;
    $per_page = is_null($per_page) ? 5 : $per_page;


    if ($per_page == -1) {
        return array('paged' => $items, 'totalPages' => 1);
    }

    $total = count($items);
    $totalPages = ceil($total / $per_page);
    $page = max($page, 1);
    //$page = min($page, $totalPages);
    $offset = ($page - 1) * $per_page;
    if ($offset < 0) $offset = 0;

    $paged = array_slice($items, $offset, $per_page);

    return array('paged' => $paged, 'totalPages' => $totalPages);
}


add_action('rest_api_init', 'adding_collection_meta_rest');
add_action('rest_api_init', 'adding_video_meta_rest');
add_action('rest_api_init', 'adding_category_meta_rest');

function adding_collection_meta_rest()
{
    register_rest_field(
        'collection',
        'seasons',
        array(
            'get_callback'      => 'collection_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );

    register_rest_field(
        'collection',
        'social_media',
        array(
            'get_callback'      => 'collection_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );

    register_rest_field(
        'collection',
        'extras',
        array(
            'get_callback'      => 'collection_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );

    register_rest_field(
        'collection',
        'link_sharing',
        array(
            'get_callback'      => 'collection_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );

    register_rest_field(
        'collection',
        'season_label',
        array(
            'get_callback'      => 'collection_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );
}

function collection_meta_callback($collection, $field_name, $request)
{

    $id = $collection['id'];
    $values = array();

    switch ($field_name) {
        case 'seasons':
            $items = get_terms(
                'collection',
                array(
                    'hide_empty' => 0,
                    'parent' => $id,
                    'meta_key'       => 'collection_enable',
                    'meta_value'     => true,
                    'meta_compare'   => '='
                )
            );

            foreach ($items as $key => $item) {
                $link = 'collection/' . $collection['slug'] . '/' . $item->slug . '?s=' . $item->term_id;
                $items[$key]->link_sharing = get_site_url(null, $link);
                $items[$key]->collection_image = get_field('collection_image', 'collection_' . $item->term_id)['url'];
                $items[$key]->enable = get_field('collection_enable', 'collection_' . $item->term_id);
                $season_label = get_field('collection_season_label', 'collection_' . $item->term_id);
                $items[$key]->season_label = $season_label != "" && !is_null($season_label) ? $season_label : $item->name;
            }

            return $items;
            break;

        case 'social_media':
            $values = array(
                'redes' => get_field('redes', 'term_' . $id),
                'production' => get_field('producao', 'term_' . $id)
            );
            break;

        case 'extras':
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

        case 'link_sharing':
            $link = 'collection/' . $collection['slug'] . '?c=' . $id;
            $values =    get_site_url(null, $link);

            break;

        case 'season_label':

            $season_label = get_field('collection_season_label', 'collection_' . $id);
            $values = $season_label != "" && !is_null($season_label) ? $season_label : $collection['name'];

            break;
    }

    return $values;
}

function adding_video_meta_rest()
{
    register_rest_field(
        'video',
        'link_sharing',
        array(
            'get_callback'      => 'video_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );
    register_rest_field(
        'video',
        'taxonomies',
        array(
            'get_callback'      => 'taxonomy_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );
    register_rest_field(
        'video',
        'extras',
        array(
            'get_callback'      => 'video_extra_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );
}

function adding_category_meta_rest()
{
    register_rest_field(
        'category',
        'visible',
        array(
            'get_callback'      => 'category_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );

    register_rest_field(
        'category',
        'order',
        array(
            'get_callback'      => 'category_meta_callback',
            'update_callback'   => null,
            'schema'            => null,
        )
    );
}


function category_meta_callback($category, $field_name, $request)
{
    switch ($field_name) {
        case 'visible':
            $visible = get_field('category_visible', 'term_' . $category['id']);
            return $visible;

        case 'order':
            $order = get_field('category_order', 'term_' . $category['id']);
            return $order;
    } 
}


function video_extra_meta_callback($video, $field_name, $request)
{
    $id = $video['id'];
    $values = [];
    $items = get_field('post_extra', $id);

    foreach ($items as $item) {
        $list_videos = [];
        foreach($item['post_extra_list_videos'] as $video_item) { 
            $video_id = $video_item['post_extra_video']->ID;
            $meta = get_post_meta($video_id);
    
            $title = $video_item['post_extra_video']->post_title;
            $slug = $video_item['post_extra_video']->post_name;
            $video_type = $meta['post_video_type'][0];
            $video_episode = $meta['video_episode'][0];
            $subtitle = $meta['post_subtitle'][0];
            $description = wp_strip_all_tags($meta['post_blurb'][0]);
            $video_host = $meta['post_video_host'][0];
            $video_id = $meta['post_video_id'][0];
    
            $post_download_link = $meta['link_download_app'][0];
            $download = $meta['download'][0];
            $post_year = $meta['post_year'][0];
            $post_video_rating = $meta['post_video_rating'][0];
            $post_video_age_rating = $meta['post_video_age_rating'][0];
            $redes = get_field('redes', $id);
            $production = get_field('production', $id);
            $collection = get_the_terms($id, 'collection')[0];
    
            if ($collection) {
                $collection->parent_slug = get_term($collection->parent, 'collection')->slug;
            }
    
            $genre = get_the_terms($id, 'genre')[0];
            $category = get_the_terms($id, 'category')[0];
            $video_lenght = $meta['post_video_length'][0];
            $video_quality = $meta['post_video_quality'][0];
    
            $video_thumbnail = wp_get_attachment_image_src($meta['video_thumbnail'][0] == "" || is_null($meta['video_thumbnail'][0]) ? $meta['video_image_hover'][0] : $meta['video_thumbnail'][0])[0];
            $video_image_hover = wp_get_attachment_image_src($meta['video_image_hover'][0])[0];
    
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

    return $values;
}


function video_meta_callback($video, $field_name, $request)
{

    $genre = get_term($video['genre'][0], 'genre');
    $link = 'g/' . $genre->slug . '/' . $video['slug'] . '?v=' . $video['id'];
    $link_sharing =    get_site_url(null, $link);

    return $link_sharing;
}

function taxonomy_meta_callback($video)
{
    $taxonomy = [];
    foreach (['category', 'genre', 'collection', 'language_audio', 'language_subtitle'] as $tax) {
        $terms = get_the_terms($video['id'], $tax);

        foreach ($terms as $key => $term) {
            $term_lang = get_field('languages', $term);
            if (!empty($term_lang)) {
                $filtered_languages = [];

                foreach ($term_lang as $language) {
                    $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
                }

                $terms[$key]->languages = $filtered_languages;
            }
        }

        $taxonomy[$tax] = $terms;
    }

    return $taxonomy;
}

function echox($item)
{
    echo (json_encode($item));
    die;
}

function get_link_site_next($slug, $video_type, $collection)
{

    switch ($video_type) {


        case 'Single':
            $link = get_site_url() . "/" . $slug;
            break;

        case 'Episode':

            if ($collection->parent) {
                $parent = get_term($collection->parent, 'collection');
                $link = get_site_url() . "/c/" . $parent->slug . "/" . $collection->slug . '?target=' . $slug;
            } else {
                $link = get_site_url() . "/c/" . $collection->slug . '?target=' . $slug;
            }

            break;

        default:
            $link = site_url();
            break;
    }

    return $link;
}

// Adiciona filtro p/ metas na saida rest
// parametros:
//      meta_key
//      meta_value
// exemplo: video/?meta_key=post_video_type&meta_value=Single&per_page=5&_fields=title
add_filter("rest_video_query", "filter_rest_video_query", 10, 2);
function filter_rest_video_query($args, $request)
{
    $params = $request->get_params();

    if (isset($params['meta_key']) && isset($params['meta_value'])) {
        $args['meta_query'][] = array(
            array(
                'key'     => $params['meta_key'],
                'value'   => $params['meta_value'],
            ),
        );
    }
    return $args;
}


add_filter("rest_category_query", "filter_rest_category_query", 10, 2);
function filter_rest_category_query($args, $request)
{
    $params = $request->get_params();

    if (isset($params['meta_key']) && isset($params['meta_value'])) {
        $args['meta_query'][] = array(
            array(
                'key'     => $params['meta_key'],
                'value'   => $params['meta_value'],
            ),
        );
    }
    return $args;
}
