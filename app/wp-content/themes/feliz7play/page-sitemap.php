<?php
/**
* Template Name: Sitemap
*/

header("Content-Type: text/plain");


// GENERIC
echo get_site_url() ."/\r\n";

// POSTS
$args = array(
    'numberposts' => 10,
    'post_type'   => 'video',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_key' => 'post_video_type',
    'meta_value' => 'Single',
  );

$posts = get_posts($args);

if ( $posts ) {
    foreach ( $posts as $post ) : 
        setup_postdata( $post );
        echo get_site_url() ."/". $post->post_name ."\r\n" ;
    endforeach;
    wp_reset_postdata();
}


// GENRES
echo get_site_url() ."/g/\r\n";
$genres = get_terms( array(
    'taxonomy' => 'genre',
    'hide_empty' => true
) );
 
if ( !empty($genres) ) :
    foreach( $genres as $g ) {
        echo get_site_url() ."/g/". $g->slug ."\r\n" ;
    }
endif;


// COLLECTION
$collections = get_terms( array(
    'taxonomy' => 'collection',
    'hide_empty' => true
) );

if ( !empty($collections) ) :
    foreach( $collections as $c ) {
        if ($c->parent == 0) {
            echo get_site_url() ."/c/". $c->slug ."\r\n" ;
        } else {
            $parent  = get_term( $c->parent, 'collection' );
            echo get_site_url() ."/c/". $parent->slug ."/". $c->slug ."\r\n" ;
        }
    }
endif;


