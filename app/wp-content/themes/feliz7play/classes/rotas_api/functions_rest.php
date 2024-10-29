<?php

function get_sorted_languages($wp_object, $language_field = 'languages') {
    $languages = get_field($language_field, $wp_object);

    if (is_array($languages) && !empty($languages)) {
        $filtered_languages = [];

        foreach ($languages as $language) {
            $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
        }

        return $filtered_languages;
    }

    return 'Languages not found.';
}

function get_genre($genre_items) {
    foreach ($genre_items as $genre_item) {
        $category_items = get_sub_field('genre_category');
        if (is_array($category_items)) {
            $categories = [];
            foreach ($category_items as $category_item) {
               array_push($categories, get_category_by_line($category_item));
            }
        }

        return [
            'id' => $genre_item->term_id,
            'source' => $genre_item->taxonomy,
            'languages' => get_sorted_languages($genre_item),
            'image_default' => get_field('image', $genre_item)['url'],
            'categories' => $categories ?: []
        ];
    }
}

function get_genre_v2($genres_items) {
    $languages = get_sub_field('languages');
    if (is_array($languages) && !empty($languages)) {
        $filtered_languages = [];

        foreach ($languages as $language) {
            $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
        }
    }

    $genre_array = [];
    foreach ($genres_items as $genre_item) {
        $image = get_field('image', 'term_' . $genre_item->term_id)['url'];
        $item = get_genre_by_line($genre_item, $image);
        array_push($genre_array, $item);
    }

    $category_array = [];
    $category_items = get_sub_field('genre_category');
    foreach ($category_items as $category_item) {
        $item = get_category_by_line($category_item);
        array_push($category_array, $item);
    }

    $line = [
        'languages' => $languages,
        'source' => 'genre',
        'genres' => $genre_array,
        'categories' => $category_array
    ];

    return $line;
}

function get_genre_by_line($item, $image) {
    return [
        'id' => $item->term_id,
        'source' => $item->taxonomy,
        'image_default' => $image,
        'languages' => get_sorted_languages($item),
    ];
}

function get_category_by_line($item) {
    return [
        'id' => $item->term_id,
        'source' => $item->taxonomy,
        'languages' => get_sorted_languages($item),
    ];
}

function get_line_post($args, $limited = false) {
    $items = [];
    $controle = [];

    foreach (get_posts($args) as $post) {
        $collection = get_the_terms($id, 'collection')[0];

        if ($collection) {
            $collection->parent_slug = get_term($collection->parent, 'collection')->slug;
        }

        $values = get_post_infos($post);

        $is_single = is_array($values['languages'] ?? []) ? array_reduce($values['languages'], function ($carry, $language) {
            return $carry || ($language['video_type'] ?? '') === 'Single';
        }, false) : false;

        if ($limited != false) {
            $id_check = $is_single ? $post->ID : $collection->term_id;

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

function get_collection_infos($collection) {
    $languages = get_sorted_languages($collection);

    if (!empty($languages)) {
        foreach ($languages as $key => $language) {
            $languages[$key]['video_thumbnail'] = wp_get_attachment_image_src($language['collection_image'][0])[0];
            $languages[$key]['video_type'] = $collection->taxonomy;
        }
    }

    return [
        'id' => $collection->term_id,
        'languages' => $languages,
    ];
}

function get_post_infos($post) {
    $collection = get_the_terms($post->ID, 'collection')[0];
    if ($collection) {
        $collection->parent_slug = get_term($collection->parent, 'collection')->slug;
    }

    $languages = get_sorted_languages($post->ID);
    if (is_array($languages)) {
        foreach ($languages as $key => $language) {
            $languages[$key] = array_merge($languages[$key], [
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
                unset($languages[$key][$value]);
            }
        }
    }

    return [
        'id' => $post->ID,
        'languages' => $languages
    ];
}

function get_slider_infos($slider_object) {
    $item = $slider_object;
    $type = get_field('slider_type', $item);
    $source = get_field('slider_source', $item);
    $languages = get_sorted_languages($item, 'slider_languages');

    $images = [
        'desktop' => get_field('slider_desktop_image', $item)['url'],
        'tablet' => get_field('slider_tablet_image', $item)['url'],
        'mobile' => get_field('slider_mobile_image', $item)['url'],
    ];

    if ($type === 'video') {
        if ($source === 'video') {
            $video = get_field('slider_video_object', $item);

            return [
                'id' => $item->ID,
                'source' => $source,
                'languages' => $languages,
                'images' => $images,
                'video' => array_merge(get_post_infos($video), [
                    'genre' => get_the_terms($video->ID, 'genre')[0]->name,
                    'category' => get_the_terms($video->ID, 'category')[0],
                ]),
            ];
        }

        if ($source === 'collection') {
            $collection = get_field('to_collection', $item);

            return [
                'id' => $item->ID,
                'source' => $source,
                'languages' => $languages,
                'images' => $images,
                'collection' => get_collection_infos($collection),
            ];
        }
    }

    if ($type === 'custom') {
        return [
            'id' => $item->ID,
            'type' => $type,
            'languages' => $languages,
            'images' =>	$images
        ];
    }
}

function get_collection($item) {
    return [
        'id' => $item->term_id,
        'languages' => get_sorted_languages($item),
        'source' => $item->taxonomy,
        'seasons' => get_field('seasons', $item)
    ];
}

function get_custom($items) {
    $limited_per_item = 1;

    $languages = get_sub_field('languages');
    if (is_array($languages) && !empty($languages)) {
        $filtered_languages = [];

        foreach ($languages as $language) {
            $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
        }
    }

    $line = [
        'languages' => isset($filtered_languages) ? $filtered_languages : 'Languages not found.',
        'source' => 'custom',
        'model' => get_sub_field('model'),
        'items' => []
    ];

    foreach ($items as $item) {
        if ($item['acf_fc_layout'] === 'collection') {
            $collection = [
                'id' => $item['to_custom_collection']->term_id,
                'languages' => get_sorted_languages($item['to_custom_collection']),
            ];

            if (in_array($line['model'], ['circle', 'vertical', 'highlight'])) {
                $sufix = $line['model'] === 'circle' ? 'circle' : 'vertical';
                $collection['video_thumbnail_' . $sufix] = $item['image']['url'];
            }

            array_push($line['items'], $collection);
        }

        if ($item['acf_fc_layout'] === 'video') {
            $video = get_post_infos($item['to_video']);

            if (in_array($line['model'], ['circle', 'vertical', 'highlight'])) {
                $sufix = $line['model'] === 'circle' ? 'circle' : 'vertical';
                $collection['video_thumbnail_' . $sufix] = $item['image']['url'];
            }

            array_push($line['items'], $video);
        }

        if ($item['acf_fc_layout'] === 'slider') {
            array_push($line['items'], get_slider_infos($item['to_slider']));
        }
    }

    return $line;
}

function get_recentes() {
    $limited = get_sub_field('n_itens');
    $languages = get_sub_field('languages');
    if (is_array($languages) && !empty($languages)) {
        $filtered_languages = [];

        foreach ($languages as $language) {
            $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
        }
    }

    $line = [
        'languages' => isset($filtered_languages) ? $filtered_languages : 'Languages not found.',
        'source' => 'custom',
        'model' => 'default',
        'items' => [],
    ];

    $controle = [];
    $posts = get_posts([
        'post_type'         => 'video',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
    ]);

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

    return $line;
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

function taxonomy_meta_callback($video) {
    $taxonomy = [];
    foreach (['category', 'genre', 'collection', 'language_audio', 'language_subtitle'] as $tax) {
        $terms = get_the_terms($video['id'], $tax);

        foreach ($terms as $key => $term) {
            $terms[$key]->languages = get_sorted_languages($term);
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
