<?php

function getVideoCommId($content,$video,$idComment){
    return sendIdVideoComm($content,$video,$idComment);
}

function sendIdVideoComm($content,$video,$idComment){
  require("API/config.php"); // Sets global var $partnerId 
    $cont = $video;
    $idVideoOnKaltura = getIdExists($cont,$idComment);
    $idFlavor4webm = getFlavor($idVideoOnKaltura,"webm",$idComment);
    $urlFlavor4webm = 'http://cdnbakmi.kaltura.com/p/'.$partnerId.'/sp/'.$partnerId.'00/serveFlavor/entryId/'.$idVideoOnKaltura.'/flavorId/'.$idFlavor4webm.'/name/a.webm';
    $idFlavor4mp4 = getFlavor($idVideoOnKaltura,"mp4",$idComment);
    $urlFlavor4mp4 = 'http://cdnbakmi.kaltura.com/p/'.$partnerId.'/sp/'.$partnerId.'00/serveFlavor/entryId/'.$idVideoOnKaltura.'/flavorId/'.$idFlavor4mp4.'/name/a.mp4';
    //if($idVideoOnKaltura && $idFlavor4webm=='') {
    //  convertToWebM($idVideoOnKaltura);
    //}

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
              video_converting("'.$GLOBALS["i"].'","'.$idVideoOnKaltura.'","mp4","webm","'.$partnerId.'",200,140,0);
          else jQuery("#mainDivHtml5'.$GLOBALS["i"].'").remove();
          </script>';
  } else {
    echo '<script type="text/javascript">
        jQuery(document).ready(function ($){
          var allowFlash = swfobject.hasFlashPlayerVersion("1");
          var v = document.createElement("video");
          if(allowFlash==false && v.play){
            setVideoHtml5("'.$GLOBALS["i"].'","'.$idVideoOnKaltura.'","'.$idFlavor4mp4.'","'.$idFlavor4webm.'","'.$partnerId.'",200,140);
          }else{
            jQuery("#mainDivHtml5'.$GLOBALS["i"].'").html('.json_encode($content).');
          }
        });
            </script>';   
  }
}
?>