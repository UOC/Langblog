<?php
function convertToWebM($idVideo){
  $file = "html5Video/API/setWebM.php";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, plugin_dir_url( $file )."setWebM.php?idVideo=".$idVideo."&typeFlavor=webm");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);
}

function kaltura_parser($content){
    return preg_replace_callback('/\[kaltura-widget uiconfid="(([^]]+))]/i', sendIdVideo ,$content);
}

function sendIdVideo($matches){
	require("API/config.php"); // Sets global var $partnerId 
    $cont = explode('"', $matches[2]);
    $idVideoOnKaltura = getIdExists($cont[2]);
    $idFlavor4webm = getFlavor($idVideoOnKaltura,"webm");
    $urlFlavor4webm = 'http://cdnbakmi.kaltura.com/p/'.$partnerId.'/sp/'.$partnerId.'00/serveFlavor/entryId/'.$idVideoOnKaltura.'/flavorId/'.$idFlavor4webm.'/name/a.webm';
    $idFlavor4mp4 = getFlavor($idVideoOnKaltura,"mp4");
    $urlFlavor4mp4 = 'http://cdnbakmi.kaltura.com/p/'.$partnerId.'/sp/'.$partnerId.'00/serveFlavor/entryId/'.$idVideoOnKaltura.'/flavorId/'.$idFlavor4mp4.'/name/a.mp4';

    if($idVideoOnKaltura && $idFlavor4webm=='') {
    	convertToWebM($cont[2]);
	}

    echo '
    	<div id="mainDivHtml5'.$GLOBALS["i"].'" style="margin-top:10px; width:100%;"></div>
    	<script type="text/javascript">
        var plugin_dir_url = "'.plugins_url().'";
    		if(jQuery("#mainDivHtml5'.$GLOBALS["i"].'").html()=="") jQuery("#mainDivHtml5'.$GLOBALS["i"].'").html(setDivs("'.$GLOBALS["i"].'"));
    	</script>
    ';
    if (!$idVideoOnKaltura || strpos($idVideoOnKaltura,'not found')) {
		echo '<script type="text/javascript">
					var allowFlash = swfobject.hasFlashPlayerVersion("1");
					var v = document.createElement("video");
					if(allowFlash==false && v.play) video_not_found("'.$GLOBALS["i"].'");
	  			</script>'; 
    } elseif (!$idFlavor4webm || !$idFlavor4mp4 || strpos($idFlavor4mp4, 'not found') || strpos($idFlavor4webm, 'not found')) {
		echo '<script type="text/javascript">		
          var allowFlash = swfobject.hasFlashPlayerVersion("1");
					var v = document.createElement("video");
					if(allowFlash==false && v.play) 
              video_converting("'.$GLOBALS["i"].'","'.$idVideoOnKaltura.'","mp4","webm","'.$partnerId.'");
					else jQuery("#mainDivHtml5'.$GLOBALS["i"].'").remove();
	  			</script>';
	} else {
 		echo '<script type="text/javascript">
 				jQuery(document).ready(function ($){
 					setVideoHtml5("'.$GLOBALS["i"].'","'.$idVideoOnKaltura.'","'.$idFlavor4mp4.'","'.$idFlavor4webm.'","'.$partnerId.'",400,340);
    			});
    	  		</script>';   
	}
}

function getFlavor($idVideo,$typeFlavor) {
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

function getIdExists($idVideo) {
  global $post;
  if(get_post_meta($post->ID, 'idVideoOnKaltura', true) == null){
    $file = "html5Video/API/getIdExists.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, plugin_dir_url( $file )."getIdExists.php?idVideo=".$idVideo);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    add_post_meta($post->ID, 'idVideoOnKaltura', $data);
  }else{
    $data = get_post_meta($post->ID, 'idVideoOnKaltura', true);
  }
  return $data;
}
?>