<?php
/*
Plugin Name: Uploader Kaltura
Version: 1.1
Description: Allows you to edit your posts without going through the admin interface
Author: abertranb
Author URI: http://www.uoc.edu/
Plugin URI: http://www.uoc.edu/
Text Domain: uploader-kaltura
Domain Path: /lang

Copyright (C) 2013 uoc.edu (abertranb@uoc.edu)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
require_once ('lib/kaltura_html5_helpers.php');
if (KalturaHTML5Helpers::videoCommentsEnabled()) 
{
    add_action('comment_form', 'kaltura_html5_comment_form');
    add_action('wp_head', 'kaltura_html5_head'); // print css
    //add_action('wp_footer', 'kaltura_html5_footer'); // js css
    
}
// a workaround when using symbolic links and __FILE__ holds the resolved path
$uloader_kaltura_file = __FILE__;
if (isset($mu_plugin)) 
{
    $uloader_kaltura_file = $mu_plugin;
}
if (isset($network_plugin)) 
{
    $uloader_kaltura_file = $network_plugin;
}
if (isset($plugin)) 
{
    $uloader_kaltura_file = $plugin;
}
function kaltura_html5_head() 
{
}
function kaltura_html5_footer() 
{
    $plugin_url = KalturaHTML5Helpers::getPluginUrl();
    echo ('<script src="' . KalturaHTML5Helpers::getPluginUrl() . '/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $("#kaltura_video_comment_html5").live("click", function() {
        var url = $(this).attr("href");
        var modal_id = $(this).attr("data-controls-modal");
        $("#" + modal_id).load(url);
        return false;
    });
</script>
		');
}
function kaltura_html5_comment_form($post_id) 
{
    $plugin_url = KalturaHTML5Helpers::getPluginUrl();
    echo ('<link rel="stylesheet" href="' . KalturaHTML5Helpers::getPluginUrl() . '/css/bootstrap.min.css">
<link rel="stylesheet" href="http://getbootstrap.com/2.3.2/assets/css/bootstrap-responsive.css">');

    $user = wp_get_current_user();
    if (!$user->ID && !KalturaHTML5Helpers::anonymousCommentsAllowed()) 
    {
        echo "You must be <a href=" . get_option('siteurl') . "/wp-login.php?redirect_to=" . urlencode(get_permalink()) . ">logged in</a> to post a <br /> video comment.";
    }
    else
    {
        $plugin_url = KalturaHTML5Helpers::getPluginUrl();
        $js_click_code = "openCommentUploader('" . $plugin_url . "');return false; ";
        if (false) 
        {
            echo '
	<a data-target="#modal_kaltura_html5_' . $post_id . '" role="button" class="btn" id="kaltura_video_comment_html5" data-toggle="modal" href="' . $plugin_url . '/page_contribution_wizard_video_comment.php">Upload Video File</a>

	<div class="modal fade hide" id="modal_kaltura_html5_' . $post_id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel">Upload Video File</h3>
	  </div>
	  <div class="modal-body">
	    <p></p>
	  </div>
	  <div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
	</div>
	';
        }else{
            echo '<script language="javascript">
        jQuery( document ).ready(function() {
            var allowFlash = swfobject.hasFlashPlayerVersion("1");
            var v = document.createElement("video");
            if(allowFlash==true) jQuery("#kaltura_video_comment_html5").hide();
            else jQuery("#kaltura_video_comment").hide();
        });

        function openCommentUploader (pluginUrl) {
		var postId = jQuery("[name=comment_post_ID]").val();
		var author = jQuery("#author").val();
		var email  = jQuery("#email").val();
		KalturaModal.openModal("contribution_wizard", pluginUrl + "/page_contribution_wizard_video_comment.php?postid="+postId+"&author="+author+"&email="+email, { width: 680, height: 360 } );
		jQuery("#contribution_wizard").addClass("modalContributionWizard");
		}</script>';
            echo '<button class="btn" id="kaltura_video_comment_html5"  onclick="' . $js_click_code . '">Upload Video File</button>';
        }
    }
}
add_action('wp_ajax_my_special_action', 'my_action_uploader_kaltura_callback');
function my_action_uploader_kaltura_callback() 
{
    global $wpdb; // this is how you get access to the database
    $whatever = intval($_POST['whatever']);
    $whatever+= 10;
    echo $whatever;
    die(); // this is required to return a proper result
    
}
