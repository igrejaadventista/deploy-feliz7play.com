<?php

// PARA EXECUTAR O SCRIPT, RODE NO TERMINAL (com wpcli instalado): 
// wp eval-file setSeason.php --url="v2.feliz7play.com/es/"
// wp eval-file setSeason.php --url="v2.feliz7play.com/pt/"

function setSeasons() {
    $items = get_terms( 'collection' );	
    foreach ( $items as $item ) {
        update_field( 'seasons', false, "collection_". $item->term_id );
        update_field( 'collection_enable', true, "collection_". $item->term_id );

        if ( count( get_term_children( $item->term_id, 'collection' ) ) > 0 ) {	
            $count++;
            echo $count ." - " . $item->term_id .", ". $item->name . "\n";
            update_field( 'seasons', true, "collection_". $item->term_id );
            // update_field( 'acf-field_6006cf48cafa5', 'true', $item->term_id );
        }
    }
}

setSeasons();