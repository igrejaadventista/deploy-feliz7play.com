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
	
	foreach ($posts as &$post){
		sleep(0.5);
		$count++;

		$video_source = get_field("post_video_host", $post->ID);
		$video_id = get_field("post_video_id", $post->ID);

		switch ($video_source) {
			case "Youtube":
				// https://www.googleapis.com/youtube/v3/videos?id=YOUTUBE_API_KEY&part=contentDetails

				$json = file_get_contents("https://api.feliz7play.com/v4/youtubeinfo?video_id=". $video_id );
				$obj = json_decode($json);
				
				$time = $obj->time;
				$size = $obj->quality;
				$release_data = date('Y', strtotime($obj->release_date));

				if ($obj) {
					echo "\e[39m". $count ." - ". $video_source ." - ". $post->ID ." - ". $video_id ." - ". $time ." - ". $size ." - ". $release_data; 
					update_field( 'post_video_length', $time, $post->ID );
					update_field( 'post_video_year', $release_data, $post->ID );

				} else {
					echo "\e[31m". $count ." - ". $video_source ." - ". $post->ID ." - ". $video_id ." - Vídeo inválido"; 
					wp_update_post(array(
						'ID'    =>  $post->ID,
						'post_status'   =>  'draft'
					));
				}
				break;
				
			case "Vimeo":
				$json = file_get_contents("https://api.feliz7play.com/v4/pt/vimeoinfo?video_id=". $video_id);
				$obj = json_decode($json);

				$time = $obj->time;
				$release_data = date('Y', strtotime($obj->release_date));

				if ( $time ) {
					echo "\e[39m". $count ." - ". $video_source ." - ". $post->ID ." - ". $video_id ." - ". $time ." - ". date('Y', strtotime($release_data)); 
					update_field( 'post_video_length', $time, $post->ID );
					update_field( 'post_video_year', $release_data, $post->ID );
				} else {
					echo "\e[31m". $count ." - ". $video_source ." - ". $post->ID ." - ". $video_id ." - Vídeo inválido"; 
					wp_update_post(array(
						'ID'    =>  $post->ID,
						'post_status'   =>  'draft'
					));
				}
				break;

		}
		echo "\n";
	}
}

getVideoInfos();
