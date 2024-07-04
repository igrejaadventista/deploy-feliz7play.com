<?php

// PARA EXECUTAR O SCRIPT, RODE NO TERMINAL (com wpcli instalado): 
// wp eval-file setCollection.php --url="v2.feliz7play.com/es/"
// wp eval-file setCollection.php --url="v2.feliz7play.com/pt/"

function setCollectoin() {
	$args = array(
		"post_type"		    => "video",
		"posts_per_page"    => "-1",
		"post_status"       => "publish"
	);
	$posts = get_posts($args);
	
	echo "\n\n";
	echo "POSTS A PROCESSAR: ". count($posts);
	echo "\n\n";

    echo "Cont\tPostID\PaID\tChildCount\n";
    $taxonomy = 'collection';
	
	foreach ($posts as &$post){
		$count++;
        
        $terms = get_the_terms( $post->ID, $taxonomy );
        $taxonomy_parent = $terms[0]->parent;

        $terms = get_term_children( $taxonomy_parent, $taxonomy );

        if (count($terms) <= 1){
            echo "\e[39m". $count ."\t". $post->ID ."\t". $taxonomy_parent ."\t". count($terms);
            // registra o post na taxonomia pai
            wp_set_post_terms( $post->ID, [ $taxonomy_parent ], $taxonomy );
            // update_field( 'seasons', false, "collection_". $taxonomy_parent );
        } else {
            echo "\e[31m". $count ."\t". $post->ID ."\t". $taxonomy_parent ."\t". count($terms);
        }

        ob_flush();
        flush();
        sleep(0.5);
		
		echo "\n";
	}
    ob_end_flush();

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,

    ]);

    foreach($terms as $t){
        if(( $t->count == 0 ) && ( $t->parent != 0 )){
            $cont++;
            echo $cont ." - ". $t->term_id ." - ". $t->count ." - ". $t->slug ."\n";
            wp_delete_term( $t->term_id, $taxonomy );
        } 

        if ( ( $t->count > 0 ) && ( $t->parent == 0 ) ){
            update_field( 'seasons', false, "collection_". $t->term_id );
        }
    }
}

setCollectoin();
