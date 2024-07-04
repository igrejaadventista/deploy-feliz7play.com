<?php

// PARA EXECUTAR O SCRIPT, RODE NO TERMINAL (com wpcli instalado): 
// wp eval-file getVideosInfos.php --url="v2.feliz7play.com/es/"
// wp eval-file getVideosInfos.php --url="v2.feliz7play.com/pt/"

function getVideoInfos() {
	$args = array(
		"post_type"		    => "video",
		"posts_per_page"    => "-1",
		"post_status"       => "publish"
	);
	$posts = get_posts($args);
	
	echo "\n\n";
	echo "POSTS A PROCESSAR: ". count($posts);
	echo "\n\n";

    $youtube = 0;
    $vimeo = 0;
	
	foreach ($posts as &$post){
		sleep(0.5);
		$count++;

		$video_source = get_field("post_video_host", $post->ID);
		$video_id = get_field("post_video_id", $post->ID);
        
		switch ($video_source) {
			case "Youtube":
                $youtube++;
				
				break;
				
			case "Vimeo":
				$vimeo++;
				break;

		}
		echo "\n";
	}

    echo $youtube;
    echo "\n";
    echo $vimeo;
    echo "\n";
}

getVideoInfos();
