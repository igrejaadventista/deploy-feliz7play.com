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

function get_genre() {
    $category_items = get_sub_field('genre_category');
    if (is_array($category_items)) {
        $categories = [];
        foreach ($category_items as $category_item) {
           array_push($categories, get_category_by_line($category_item));
        }
    }

    $genre_items = get_sub_field('genre');
    if (is_array($genre_items)) {
        $genres = [];
        foreach ($genre_items as $genre_item) {
           array_push($genres, get_genre_by_line($genre_item));
        }
    }

    return [
        'languages' => get_line_languages(),
        'source' => 'genre',
        'genres' => $genres ?: [],
        'categories' => $categories ?: [],
    ];
}

function get_genre_by_line($item) {
    return [
        'id' => $item->term_id,
        // 'source' => $item->taxonomy,
        'image_default' => get_field('image', 'term_' . $item->term_id)['url'],
        'languages' => get_sorted_languages($item),
    ];
}

function get_category_by_line($item) {
    return [
        'id' => $item->term_id,
        'languages' => get_sorted_languages($item),
    ];
}

function get_line_post($args, $limited = false) {
    $items = [];
    $controle = [];

    foreach (get_posts($args) as $post) {
        $collection = get_the_terms($post->ID, 'collection');

        if ($collection !== false && !is_wp_error($collection)) {
            $collection = $collection[0];
            $collection->parent_slug = $collection->parent ? get_term($collection->parent, 'collection')->slug : '';
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

        $video_type = $meta['languages_0_post_video_type'][0];


        $collection = get_the_terms($id, 'collection');
        $collection = ($collection !== false && !is_wp_error($collection)) ? return_parent_collection($collection[0]) : null;

        if ($video_type == 'Single') {
            $id_check =  $id;
        } else {

            if ($collection == null) {
                continue;
            }

            $meta = get_term_meta($collection->term_id);

            if ($meta['languages_0_collection_enable'][0]) {
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
    if (is_object($collection) && $collection->parent) {
        return get_term($collection->parent, 'collection');
    }

    return $collection;
}

function get_collection_infos($collection) {
    $languages = get_sorted_languages($collection);

    if (!empty($languages)) {
        foreach ($languages as $key => $language) {
            $languages[$key]['video_thumbnail'] = isset($language['collection_image']) && is_array($language['collection_image']) ? $language['collection_image']['url'] : '';
            $languages[$key]['video_type'] = $collection->taxonomy;
        }
    }

    return [
        'id' => $collection->term_id,
        'languages' => $languages,
    ];
}

function get_post_infos($post) {
    $collection = get_the_terms($post->ID, 'collection');

    if ($collection !== false && !is_wp_error($collection)) {
        $collection = $collection[0];
        $collection->parent_slug = $collection->parent ? get_term($collection->parent, 'collection')->slug : '';
        $collection_data = get_collection_infos($collection);
    }

    $category = get_the_terms($post->ID, 'category')[0];
    if ($category) {
        $category_data = get_collection_infos($category);
    }

    $genre = get_the_terms($post->ID, 'genre')[0];
    if ($genre) {
        $genre_data = get_collection_infos($genre);
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
                'video_thumbnail' => is_array($language['video_thumbnail']) ? $language['video_thumbnail']['url'] : '',
                'video_image_hover' => is_array($language['video_image_hover']) ? $language['video_image_hover']['url'] : '',
                'image_content_header' => is_array($language['image_content_header']) ? $language['image_content_header']['url'] : '',
                'link' => get_link_site_next($language['slug'], $language['post_video_type'], $collection),
                'collection' => isset($collection_data['languages'][$key]) ? ['id' => $collection_data['id'], ...$collection_data['languages'][$key]] : [],
                'category' => isset($category_data['languages'][$key]) ? ['id' => $category_data['id'], ...$category_data['languages'][$key]] : [],
                'genre' => isset($genre_data['languages'][$key]) ? ['id' => $genre_data['id'], ...$genre_data['languages'][$key]] : [],
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
        foreach ($languages as $key => $value) {
            foreach (['title', 'description', 'button', 'button_link'] as $unused_value) {
                unset($languages[$key][$unused_value]);
            }
        }

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

function get_collection_seasons($collection_id) {
    $seasons = get_terms([
        'taxonomy' => 'collection',
        'parent' => $collection_id,
        'hide_empty' => true,
    ]);

    $filtered_seasons = [];
    foreach ($seasons as $key => $item) {
        $parent_item = get_sorted_languages(get_term($collection_id, 'collection'));
        $term_languages = get_sorted_languages($item);

        foreach ($term_languages as $key => $value) {
            foreach (['collection_image', 'collection_image_header'] as $image_field) {
                if (isset($value[$image_field]) && !empty($value[$image_field])) {
                    $term_languages[$key][$image_field] = $value[$image_field]['url'];
                }
            }

            foreach (['collection_category', 'collection_genre'] as $taxonomy_field) {
                if (isset($term_languages[$key][$taxonomy_field]) && !empty($term_languages[$key][$taxonomy_field])) {
                    $taxonomy_data = [];

                    if (is_array($term_languages[$key][$taxonomy_field])) {
                        foreach ($term_languages[$key][$taxonomy_field] as $term) {
							$sub_term_languages = get_field('languages', $term) ?: [];
							foreach ($sub_term_languages as $language) {
								if ($language['language'] === $key) {
									unset($language['language']);
									$term->name = isset($language['title']) ? $language['title'] : '';
									$term->slug = isset($language['slug']) ? $language['slug'] : '';
									$term->description = isset($language['description']) ? $language['description'] : '';
									$taxonomy_data[] = $term;
								}
							}
						}
                    } else {
                        $term = $term_languages[$key][$taxonomy_field];
                        $sub_term_languages = get_field('languages', $term) ?: [];
                        foreach ($sub_term_languages as $language) {
                            if ($language['language'] === $key) {
                                unset($language['language']);
                                $term->name = isset($language['title']) ? $language['title'] : '';;
                                $term->slug = isset($language['slug']) ? $language['slug'] : '';;
                                $term->description = isset($language['description']) ? $language['description'] : '';
                                $taxonomy_data = $term;
                            }
                        }
                    }

                    $term_languages[$key][$taxonomy_field] = $taxonomy_data;
                }
            }

            $term_languages[$key]['link_sharing'] = get_site_url() . '/' . $item->taxonomy . '/' . $parent_item[$key]['slug'] . '/' . $term_languages[$key]['slug'] . '?c=' . $item->term_id;

            $term_languages[$key]['enable'] = $term_languages[$key]['collection_enable'];
            unset($term_languages[$key]['collection_enable']);

            $term_languages[$key]['season_label'] = $term_languages[$key]['collection_season_label'];
            unset($term_languages[$key]['collection_season_label']);
        }

        $item->languages = $term_languages;
        array_push($filtered_seasons, $item);
    }

    return $filtered_seasons;
}

function get_line_languages() {
    $languages = get_sub_field('languages');
    if (is_array($languages) && !empty($languages)) {
        $filtered_languages = [];

        foreach ($languages as $language) {
            $filtered_languages[$language['language']] = array_diff_key($language, ['language' => '']);
        }
    }
    return $filtered_languages;
}

function get_collection($item) {
    return [
        'id' => $item->term_id,
        'source' => $item->taxonomy,
        'languages' => get_sorted_languages($item),
        'seasons' => get_collection_seasons($item->term_id),
    ];
}

function get_custom($items) {
    $limited_per_item = 1;

    $languages = get_line_languages();

    $line = [
        'languages' => isset($languages) ? $languages : 'Languages not found.',
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
    $languages = get_line_languages();

    $line = [
        'languages' => isset($languages) ? $languages : 'Languages not found.',
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

        $video_type = is_array($meta['languages_0_post_video_type']) ? $meta['languages_0_post_video_type'][0] : $meta['languages_0_post_video_type'];

        $collection = get_the_terms($id, 'collection');
        $collection = ($collection !== false && !is_wp_error($collection)) ? return_parent_collection($collection[0]) : null;
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

function pagination_array($page, $per_page, $items = array())
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

function adding_collection_meta_rest() {
    register_rest_field('collection', 'seasons', [
        'get_callback'    => 'collection_meta_callback',
        'update_callback' => null,
        'schema'          => null,
    ]);

    register_rest_field('collection', 'link_sharing', [
        'get_callback'    => 'collection_meta_callback',
        'update_callback' => null,
        'schema'          => null,
    ]);
}

function collection_meta_callback($collection, $field_name, $request) {
    $id = $collection['id'];
    switch ($field_name) {
        case 'seasons':
            return get_collection_seasons($id);

        case 'link_sharing':
            $link = 'collection/' . $collection['slug'] . '?c=' . $id;
            return get_site_url(null, $link);
    }
}

function adding_video_meta_rest() {
    register_rest_field('video', 'link_sharing', [
        'get_callback'    => 'video_meta_callback',
        'update_callback' => null,
        'schema'          => null,
    ]);

    register_rest_field('video', 'taxonomies', [
        'get_callback'    => 'taxonomy_meta_callback',
        'update_callback' => null,
        'schema'          => null,
    ]);
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

        if (is_array($terms)) {
            foreach ($terms as $key => $term) {
                $terms[$key]->languages = get_sorted_languages($term);
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

            if (is_object($collection)) {
                if ($collection->parent) {
                    $parent = get_term($collection->parent, 'collection');
                    $link = get_site_url() . "/c/" . $parent->slug . "/" . $collection->slug . '?target=' . $slug;
                } else {
                    $link = get_site_url() . "/c/" . $collection->slug . '?target=' . $slug;
                }
            } else {
                $link = get_site_url() . "/" . $slug;
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
