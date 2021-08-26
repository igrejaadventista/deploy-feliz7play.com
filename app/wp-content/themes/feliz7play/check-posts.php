<?php
/**
* Template Name: Check Posts
*/

?>

<style> 

* {
  margin:0;
  padding: 0;
  box-sizing: border-box;
}

a, a:visited, a:hover, a:active {
  color:#0917c2;
}

tr:nth-child(even){
  background-color: #f2f2f2;
}

tr:hover {background-color: #ddd;}

td, th {
  border: 1px solid #ddd;
  padding: 2px;
}

table, th, td {
  border: none;
}

.erro{
  background-color: red
}

thead {
  background-color: silver; 
}
th{
  background-color: silver; 
  height: 60px;
  text-align: center;
}


</style>


<?php

  $target = $_GET["target"];
  cconsole($target);

  $site = get_site();

  if(!$_GET["target"]){
    ?>

    <ul>
  <li><a href="?target=single" >Single</a></li>
  <li><a href="?target=collection" >Collection</a></li>
  <li><a href="?target=episode" >Episode</a></li>
  <li><a href="?target=genre" >Genre</a></li>
</ul>

<?php
 
  } 
  else {
    ?>

    <table style='width:100%;'>
      <tbody>

<?php

    switch ($_GET["target"]) {
      case 'single':
        ?>

            <tr>
              <th colspan="8">Single</th>
            </tr>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Video ID</th>
              <th>Genre</th>
              <th>Descrição</th>
              <th>Thumbnail</th>
              <th>Image hover</th>
            </tr>
     
       

  

            <?php

            

            
              // POSTS
              $args = array(
                'post_type'   => 'video',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'meta_key' => 'post_video_type',
                'meta_value' => 'Single',
                'orderby' => 'title',
                'order'   => 'ASC',
              );

              $posts = get_posts($args);

              if ( $posts ) {
                foreach ( $posts as $post ) :
            
          
                  $id = $post->ID;
                  $name = $post->post_title;
                  $slug = $post->post_name;
                  $cod = get_field('post_video_id', $id);
                  $genre = get_the_terms($id, 'genre')[0]->name;
                  $description = get_field('post_blurb', $id);
                  $thumbnail = get_field('video_thumbnail', $id);
                  $image_hover = get_field('video_image_hover', $id);
                  $link_wp = "https://v2.feliz7play.com" . $site->path . "wp-admin/post.php?post=" . $id . "&action=edit";
                  $link_nx = "https://next.feliz7play.com" . $site->path . $slug;

                  ?>

                    <tr>
                      <td                                             ><a href="<?= $link_wp ?>" target="_blank"><?= $id ?></a></td>
                      <td class="<?= $name ? '' : 'erro' ?>"          ><a href="<?= $link_nx ?>" target="_blank"><?= $name ?></a></td>
                      <td                                             ><?= $slug ?></td>
                      <td class="<?= $cod != $cod_2 ? '' : 'erro' ?>" ><?= $cod ?></td>
                      <td class="<?= $genre ? '' : 'erro' ?>"         ><?= $genre ?></td>
                      <td class="<?= $description ? '' : 'erro' ?>"   ><?= $description ? 'OK' : 'OFF' ?></td>
                      <td class="<?= $thumbnail ? '' : 'erro' ?>"     ><?= $thumbnail ? 'OK' : 'OFF' ?></td>
                      <td class="<?= $image_hover ? '' : 'erro' ?>"   ><?= $image_hover ? 'OK' : 'OFF' ?></td>
                    </tr>

                  <?php

                  $cod_2 = $cod;
      
                endforeach;
                wp_reset_postdata();
              }

      break;
      case 'collection':
  
        ?>

        <tr style="text-align: center;">
          <th colspan="8">Collection</th>
        </tr>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Cont</th>
          <th>Genre</th>
          <th>Descrição</th>
          <th>Thumbnail</th>
          <th>Image header</th>
        </tr>

        <?php

        // Collection
        $terms = get_terms( 'collection', array( 'hide_empty' => false) );

        if ( $terms ) {
          foreach ( $terms as $term ) :


            $id = $term->term_id;
            $name = $term->name;
            $slug = $term->slug;
            $cont = $term->count;
            $genre = get_field('collection_genre', $term->taxonomy . '_' . $id)->name;
            $description = term_description($id);

            $thumbnail = get_field('collection_image',  $term->taxonomy . '_' . $id);
            $image_header = get_field('collection_image_header', $term->taxonomy . '_' . $id);

            $link_wp = "https://v2.feliz7play.com". $site->path . "wp-admin/term.php?taxonomy=collection&tag_ID=" . $id . "&post_type=video&wp_http_referer=%2Fpt%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dcollection%26post_type%3Dvideo";

            if($term->parent){
              $parent = get_term($term->parent, 'collection'); 
              $link_nx = "https://next.feliz7play.com" . $site->path . "c/" . $parent->slug . "/" . $term->slug;
            }else{
              $link_nx = "https://next.feliz7play.com" . $site->path . "c/" . $term->slug;
            }


            ?>

            <tr>
              <td                                                               ><a href="<?= $link_wp ?>" target="_blank"><?= $id ?></a></td>
              <td class="<?= $name ? '' : 'erro' ?>"                            ><a href="<?= $link_nx ?>" target="_blank"><?= $name ?></a></td>
              <td                                                               ><?= $slug ?></td>
              <td class="<?= ($term->parent && !$cont) ? 'erro' : '' ?>"         ><?= $cont ?: '' ?></td>
              <td class="<?= ($genre || $term->parent) ? '' : 'erro' ?>"        ><?= $genre ?></td>
              <td class="<?= $description ? '' : 'erro' ?> "                    ><?= $description ? 'OK' : 'OFF' ?></td>
              <td class="<?= $thumbnail ? '' : 'erro' ?>"                       ><?= $thumbnail ? 'OK' : 'OFF' ?></td>
              <td class="<?= $image_header ? '' : 'erro' ?>"                    ><?= $image_header ? 'OK' : 'OFF' ?></td>
            </tr>
      
            <?php

          endforeach;
          wp_reset_postdata();
        }

      break;
      case 'episode':      
        
        ?>

        <tr style="text-align: center;">
          <th colspan="8">Episode</th>
        </tr>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Video ID</th>
          <th>Genre</th>
          <th>Collection</th>
          <th>Thumbnail</th>
          <th>Image hover</th>
        </tr>

        <?php

        // Episode
        $args = array(
          'post_type'   => 'video',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          'meta_key' => 'post_video_type',
          'meta_value' => 'Episode',
          'orderby' => 'title',
          'order'   => 'ASC',
        );

        $posts = get_posts($args);

        if ( $posts ) {
          foreach ( $posts as $post ) :


            $id = $post->ID;
            $name = $post->post_title;
            $slug = $post->post_name;
            $cod = get_field('post_video_id', $id);
            $genre = get_the_terms($id, 'genre')[0]->name;
            $collection = get_the_terms($id, 'collection')[0];
            $image_hover = get_field('video_image_hover', $id);
            $link_wp = "https://v2.feliz7play.com". $site->path . "wp-admin/post.php?post=" . $id . "&action=edit";
            
            if($collection->parent){
              $parent = get_term($collection->parent, 'collection'); 
              $link_nx = "https://next.feliz7play.com" . $site->path . "c/" . $parent->slug . "/" . $collection->slug . '?target='. $slug;
            }else{
              $link_nx = "https://next.feliz7play.com" . $site->path . "c/" . $collection->slug . '?target='. $slug;
            }


            ?>

          <tr>
            <td                                             ><a href="<?= $link_wp ?>" target="_blank"><?= $id ?></a></td>
            <td class="<?= $name ? '' : 'erro' ?>"          ><a href="<?= $link_nx ?>" target="_blank"><?= $name ?></a></td>
            <td                                             ><?= $slug ?></td>
            <td class="<?= $cod != $cod_2 ? '' : 'erro' ?>" ><?= $cod ?></td>
            <td class=""                                    ><?= $genre ?></td>
            <td class="<?= $collection ? '' : 'erro' ?>"    ><?= $collection->name ?></td>
            <td                                             ><?= $thumbnail ? 'OK' : 'OFF' ?></td>
            <td class="<?= $image_hover ? '' : 'erro' ?>"   ><?= $image_hover ? 'OK' : 'OFF' ?></td>
          </tr>
        
            <?php

            $cod_2 = $cod;

          endforeach;
          wp_reset_postdata();
        }

      break;
      default:
        
        ?>


        <tr style="text-align: center;">
          <th colspan="8">Genre</th>
        </tr>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Cont</th>
          <th> - </th>
          <th> - </th>
          <th>Thumbnail</th>
          <th> - </th>
        </tr>

        <?php

        // Collection
        $terms = get_terms( 'genre', array( 'hide_empty' => false));

      

        if ( $terms ) {
          foreach ( $terms as $term ) :


            $id = $term->term_id;
            $name = $term->name;
            $slug = $term->slug;
            $cont = $term->count;
            $thumbnail = get_field('image',  $term->taxonomy . '_' . $id);
            
            $link_wp = "https://v2.feliz7play.com". $site->path . "wp-admin/term.php?taxonomy=genre&tag_ID=" . $id . "&post_type=video&wp_http_referer=%2Fpt%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dgenre%26post_type%3Dvideo";
            $link_nx = "https://next.feliz7play.com". $site->path . "g/" . $slug;


          ?>

            <tr>
              <td                                           ><a href="<?= $link_wp ?>" target="_blank"><?= $id ?></a></td>
              <td class="<?= $name ? '' : 'erro' ?>"        ><?= $cont ? '<a href="'. $link_nx . '" target="_blank">'. $name . '</a>' : $name ?></td>
              <td                                           ><?= $slug ?></td>
              <td class="<?= $cont > 1 ? '' : 'erro' ?>"    ><?= $cont ?></td>
              <td ></td>
              <td ></td>
              <td class="<?= $thumbnail ? '' : 'erro' ?>"   ><?= $thumbnail ? 'OK' : 'OFF' ?></td>
              <td ></td>
            </tr>
      
          <?php

          endforeach;
          wp_reset_postdata();
        }

      break;
    }
  }

?>

  </tbody>
</table>