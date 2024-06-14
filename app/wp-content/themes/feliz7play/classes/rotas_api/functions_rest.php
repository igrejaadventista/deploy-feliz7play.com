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
    $redes =                get_field('redes', $id);
    $production =           get_field('production', $id);
    $collection =           get_the_terms($id, 'collection')[0];

    if ($collection) {
        $collection->parent_slug = get_term($collection->parent, 'collection')->slug;
    }

    $genre =                get_the_terms($id, 'genre')[0];
    $video_lenght =         $meta['post_video_length'][0];
    $video_quality =        $meta['post_video_quality'][0];

    $video_thumbnail =      wp_get_attachment_image_src($meta['video_thumbnail'][0] == "" || is_null($meta['video_thumbnail'][0]) ? $meta['video_image_hover'][0] : $meta['video_thumbnail'][0])[0];
    $video_image_hover =    wp_get_attachment_image_src($meta['video_image_hover'][0])[0];

    $link =                 get_link_site_next($slug, $video_type, $collection);

    return array(
        'id' => $id,
        'title' => $title,
        'slug' => $slug,
        'video_type' => $video_type,
        'video_episode' => $video_episode,
        'subtitle' => $subtitle,
        'description' => $description,
        'genre' => $genre,
        'collection' => $collection,
        'video_host' => $video_host,
        'video_id' => $video_id,
        'post_download_link' => $post_download_link,
        'download' => $download,
        'year' => $post_year,
        'video_rating' => $post_video_rating,
        'video_thumbnail' => $video_thumbnail,
        'video_image_hover' => $video_image_hover,
        'post_video_length' => $video_lenght,
        'post_video_quality' => $video_quality,
        'redes' => $redes,
        'production' => $production,
        'link' => $link
    );
}


function get_line_collection($args)
{
    $items = array('included' => [], 'exclude' => []);

    $collections = get_terms($args);

    foreach ($collections as $collection) {

        $id = $collection->term_id;

        $meta = get_term_meta($id);

        // echox($meta);

        $title = $collection->name;
        $slug = $collection->slug;
        $video_type = $collection->taxonomy;
        $video_thumbnail = wp_get_attachment_image_src($meta['collection_image'][0])[0];
        $video_image_hover = false;

        $values = array(
            'id' => $id, 
            'title' => $title, 
            'slug' => $slug, 
            'video_type' => $video_type, 
            'video_thumbnail' => $video_thumbnail, 
            'video_image_hover' => $video_image_hover
        );
        // $values = array('id' => $id);

        array_push($items['included'], $values);

        // $args = array('post_type' => 'video', 'fields' => 'ids', 'collection' => $slug, 'numberposts' => -1);
        // $exclude = get_posts($args);
        // array_push($items['exclude'], ...$exclude);
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
                    'include' => $item['slider_video_object']->ID, 'numberposts' => 0
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
            // $values = array(
            //     'title' => print_r($items, true),
            // );

            foreach ($items as $item) {
                $list_videos = [];
                foreach($item['extra_list_video'] as $video) { 
                    // $args = array(
                    //     'post_type' => 'video', 
                    //     'fields' => '', 
                    //     'include' => $video->ID, 'numberposts' => 0
                    // );
                    // $post = get_line_post($args);
                    // $list_videos[] = $post;

                    $list_videos = print_r($video, true);
                }

                $values[] = array(
                    'title' => $item['extra_titulo'],
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

    $taxonomy = array(
        'genre' => get_the_terms($video['id'], 'genre'),
        'collection' => get_the_terms($video['id'], 'collection')
    );

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
