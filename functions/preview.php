<?php

header('Access-Control-Allow-Origin: *');

include_once('../../../../wp-load.php');

include_once('../chimppress.php');

$campaigncontent = new MCAPI(get_option('chimppress_api_key'));

$args = array( 'numberposts' => 1, 'orderby' => 'rand' );

$rand_posts = get_posts( $args );

$rand_post = $rand_posts[0];

$content = nl2br($rand_post->post_content);

$title = $rand_post->post_title;

$link = $rand_post->guid;

$html=$_POST['html'];

$html = str_replace('%content%', $content, $html);

$html = str_replace('%title%', $title, $html);

$html = str_replace('%link%', $link, $html);

echo stripcslashes( $html );

/*
$contentstuff = $campaigncontent->inlineCss($html, true);

if ($campaigncontent->errorCode){
	echo "\n\tCode=".$campaigncontent->errorCode;
	echo "\n\tMsg=".$campaigncontent->errorMessage."\n";
} else {
	echo stripcslashes($contentstuff."\n");
}
*/

?>