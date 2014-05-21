
jQuery("#noVideo").show();
var flavorVid1=0;
var flavorVid2=0;

function build_video(idDiv,flavors,w,h) {
	flavorArray = flavors.split(";");
	src_html = '';
	for (var i=0;i<flavorArray.length;i++) {
		src = flavorArray[i].split(',');
		src_html += '<source src="' + src[1] + '" type="video/' + src[0] + '">';
	}
	jQuery("#noVideo"+idDiv).hide();
	jQuery("#videoViewer"+idDiv).html("<video id='video"+idDiv+"' width='"+w+"' height='"+h+"' controls></video>");
	jQuery("#video"+idDiv).html(src_html);
	jQuery("#loading_cover"+idDiv).hide();
	jQuery("#videoViewer"+idDiv).css("opacity", "1.0");
	jQuery("#videoViewer"+idDiv).show();
	jQuery("a:contains('Download')").parent("span").remove();
	jQuery("a:contains('Download')").parent("div").remove();	
}

function video_not_found(idDiv){
	jQuery("#loading_cover"+idDiv).fadeOut();
	jQuery("#videoViewer"+idDiv).css("opacity", "1.0");
	jQuery("#videoViewer"+idDiv).fadeIn();
	jQuery("#noVideo"+idDiv).html('El vídeo no se encuentra disponible.');
	jQuery("#noVideo"+idDiv).show();
}

function video_converting(idDiv,idVideo,typeFlavor1,typeFlavor2,partnerId,w,h){
	jQuery("#loading_cover"+idDiv).fadeOut();
	jQuery("#videoViewer"+idDiv).css("opacity", "1.0");
	jQuery("#videoViewer"+idDiv).fadeIn();
	jQuery("#noVideo"+idDiv).html('El vídeo se está convirtiendo, en breve aparecerá.');
	jQuery("#noVideo"+idDiv).show();
	if(flavorVid1==0){
		$.ajax({
	      type: 'GET',
	      url: plugin_dir_url+'/html5Video/API/getFlavor.php?idVideo='+idVideo+'&typeFlavor='+typeFlavor1,
	      data: {},
	      dataType: "html",
	      success: function(id){
	        flavorVid1 = id;
	      }
	    });
	}
	if(flavorVid1==0){
	    $.ajax({
	      type: 'GET',
	      url: plugin_dir_url+'/html5Video/API/getFlavor.php?idVideo='+idVideo+'&typeFlavor='+typeFlavor2,
	      data: {},
	      dataType: "html",
	      success: function(id){
	        flavorVid2 = id;
	      }
	    });
	}
	if(flavorVid1 !=0 && flavorVid2!=0) setVideoHtml5(idDiv,idVideo,flavorVid1,flavorVid2,partnerId,w,h);
	else setTimeout(video_converting,5000,idDiv,idVideo,typeFlavor1,typeFlavor2,partnerId,w,h);
}


function setVideoHtml5(idDiv,idVideoOnKaltura,idFlavor4mp4,idFlavor4webm,partnerId,w,h){
	var allowFlash = swfobject.hasFlashPlayerVersion("1");
	var v = document.createElement("video");
	if(allowFlash==false && v.play){
	urlFlavor4webm = 'http://cdnbakmi.kaltura.com/p/'+partnerId+'/sp/'+partnerId+'00/serveFlavor/entryId/'+idVideoOnKaltura+'/flavorId/'+idFlavor4webm+'/name/a.webm';
    urlFlavor4mp4 = 'http://cdnbakmi.kaltura.com/p/'+partnerId+'/sp/'+partnerId+'00/serveFlavor/entryId/'+idVideoOnKaltura+'/flavorId/'+idFlavor4mp4+'/name/a.mp4';
		build_video(idDiv,"mp4,"+urlFlavor4mp4+";webm,"+urlFlavor4webm,w,h);
	}else jQuery("#mainDivHtml5"+idDiv).remove();
}

function setDivs(id){
	return "<div id='noVideo"+id+"'></div><div id='viewport"+id+"'><div id='videoViewer"+id+"' style='opacity:0;'></div></div><div style='clear:both'></div>";
}