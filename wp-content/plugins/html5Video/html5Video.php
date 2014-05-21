<?php
/*
Plugin Name: html5 [video]
Plugin URI: http://www.uoc.edu
Description: Plugin para vídeo en html5
Version: 1.0
Author: Christian Moya
Author URI: http://www.uoc.edu
Plugin Image: 
*/

function html5Video(){   
    wp_register_script( 'transitjs', '/wp-content/plugins/html5Video/js/transit.js', array( 'jquery' ) );
    wp_enqueue_script('transitjs');

    

    wp_register_script( 'swfobjectjs', '/wp-content/plugins/html5Video/js/swfobject.js', array( 'jquery' ) );
    wp_enqueue_script('swfobjectjs');

   	wp_register_style('transit', '/wp-content/plugins/html5Video/css/transit.css');
	wp_enqueue_style('transit');
	include "./wp-content/plugins/html5Video/html5Video2.php";
}

	$required_plugin = 'TransitComenta_popcorn/TransitComenta_popcorn.php';
	$plugins = get_option('active_plugins');
	if ( !in_array( $required_plugin , $plugins) ) {
		add_action('edit_post', 'onSavePost');
		add_action('save_post', 'onSavePost');
		add_action('publish_post', 'onSavePost');
		add_action('edit_page_form', 'onSavePost');
		add_action('the_content', 'showVideo');
		add_filter('comment_text', 'kaltura_parserIntroComments',99);
	}


require "html5VideoInc.php";
require "html5VideoCommInc.php";

wp_register_script('html5VideoInc', '/wp-content/plugins/html5Video/js/html5VideoInc.js');
wp_enqueue_script('html5VideoInc');


function showComment($content){
	$GLOBALS["i"]++;
	$contentKaltura = explode("entry_id/",$content);
	$contentKaltura = explode("/", $contentKaltura[1]);
	$contentKaltura = $contentKaltura[0];
	if($contentKaltura=="") return $content;
	else{
		$content = getVideoCommId($content,$contentKaltura);
		return $content;
	}
}

function kaltura_parserIntroComments ($content){
	$content = showComment($content);
	return $content;
}


global $i;
$GLOBALS["i"]=0;
function showVideo($content){
	$GLOBALS["i"]++;
	$content.= html5Video();
	return $content;
}

function onSavePost($id_post){
	$content_post = get_post($id_post);
	$content = $content_post->post_content;
	kaltura_parserIntro($content);
}

function kaltura_parserIntro($content){
    return preg_replace_callback('/\[kaltura-widget uiconfid="(([^]]+))]/i', sendIdVideoIntro ,$content );
}

function getFlavorIntro($idVideo,$typeFlavor) {
	global $post;
	if(get_post_meta($post->ID, 'idFlavorOnKaltura-'.$typeFlavor, true) == null){
    	  $file = "html5Video/API/getFlavor.php";
		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, plugin_dir_url( $file )."getFlavor.php?idVideo=".$idVideo."&typeFlavor=".$typeFlavor);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  $data = curl_exec($ch);
		  curl_close($ch);
		  add_post_meta($post->ID, 'idFlavorOnKaltura-'.$typeFlavor, $data);
    }else{
    	$data = get_post_meta($post->ID, 'idFlavorOnKaltura-'.$typeFlavor, true);
    }
   	return $data;
}

function sendIdVideoIntro($matches){
    $cont = explode('"', $matches[2]);
    if(getFlavorIntro($cont[2],"webm")==""){
    	 //error_log("Envia a Convertir: ".getFlavorIntro($cont[2],"webm"));
		$file = "html5Video/API/setWebM.php";
	  	$ch = curl_init();
	  	curl_setopt($ch, CURLOPT_URL, plugin_dir_url( $file )."setWebM.php?idVideo=".$cont[2]);
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  	$data = curl_exec($ch);
	  	curl_close($ch);
	}
}

?>