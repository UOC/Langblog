<?php 
/**
 * PopUp to upload a video
 * @author Antoni Bertran (abertranb@uoc.edu) - Universitat Oberta de Catalunya
 * @copyright 2013 Universitat Oberta de Catalunya
 * @package stepsLB
 * @version 27: LBsteps.php 2013-11-04 09:15:09Z abertran $
 * @license GPL
 * Date November 2013
 */
define('WP_USE_THEMES', false);
require('../../../wp-blog-header.php');
require_once('lib/kaltura_html5_helpers.php');
	
KalturaHTML5Helpers::force200Header();

$js_error = "";
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap CSS Toolkit styles -->
<link rel="stylesheet" href="http://blueimp.github.io/cdn/css/bootstrap.min.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/css/style.css">
<!-- Bootstrap styles for responsive website layout, supporting different screen sizes -->
<link rel="stylesheet" href="http://blueimp.github.io/cdn/css/bootstrap-responsive.min.css">
<!-- Bootstrap CSS fixes for IE6 -->
<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.io/cdn/css/bootstrap-ie6.min.css"><![endif]-->
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/css/jquery.fileupload-ui.css">
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/js/kaltura.js"></script>


<?php if ($js_error != ""): ?>
<script type="text/javascript">
var topWindow = Kaltura.getTopWindow();
topWindow.Kaltura.doErrorFromComments("<?php echo $js_error; ?>");
</script>
<?php else: ?>		
<script type="text/javascript">
var entryId = "";
var topWindow = Kaltura.getTopWindow();
function onContributionWizardAfterAddEntry(obj)
{
	if (obj && obj.length > 0)
	{
		entryId = (obj[0].entryId) ? obj[0].entryId : obj[0].uniqueID;
	}
}

function onContributionWizardClose(modified)
{
	setTimeout("onContributionWizardCloseTimeouted("+modified+");");
}

function onContributionWizardCloseTimeouted(modified)
{
	if (modified) 
	{
		if (!entryId)
			topWindow.Kaltura.doErrorFromComments("Failed to add your comment");
		
		var jqComments = topWindow.jQuery("#comment,[name=comment]");
		var jqSubmitButton = topWindow.jQuery("#submit,[name=submit]");
		var widgetHtml = '[kaltura-widget entryid="'+entryId+'" size="comments" /]';
		
		if (jqComments.size() > 0 && jqSubmitButton.size() > 0)
		{
			// get only the first submit button that was found
			jqSubmitButton = jQuery(jqSubmitButton[0]);
			
			var html = jqComments.val();
			if (html.replace(" ", "") != "")
				html += "\n";
			
			html += widgetHtml;
			jqComments.val(html);
			jqComments.attr('readonly', true);
			jqSubmitButton.click();
			jqSubmitButton.val("Please wait...");
			jqSubmitButton.attr("disabled", true);
		}
		
		topWindow.KalturaModal.closeModal();
	}
	else
	{
		topWindow.KalturaModal.closeModal();
	}
}
</script>
</head>
<!-- The fileinput-button span is used to style the file input field as button -->
<div class="header">
<a class="close" id="close_popup">×</a>
</div>
<div class="container">
<div class="row text-center">
		<h2>Upload Video File</h2>
	    <span class="btn btn-primary fileinput-button">
	        <i class="icon-plus icon-white"></i>
	        <span>Select file...</span>
	        <!-- The file input field used as target for the file upload widget -->
	        <input id="fileupload" type="file" name="files[]" multiple>
	    </span>
	    <h3>Drop file here to attach it</h3>
	    <br>
	    <br>
	    <!-- The global progress bar -->
	    <div id="progress" class="progress progress-success progress-striped">
	        <div class="bar"></div>
	    </div>
	    <!-- The container for the uploaded files -->
	    <div id="files" class="files"></div>
</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/js/jquery.fileupload.js"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
'use strict';
// Change this to the location of your server-side upload handler:
var url = '<?php echo KalturaHTML5Helpers::getPluginUrl(); ?>/server/php/index.php';
//  var url = '<?php echo site_url();?>';
//    alert(url);
$('#fileupload').fileupload({
    url: url,
    dataType: 'json',
    done: function (e, data) {
        $.each(data.result.files, function (index, file) {
        	entryId = file.entryid;
            var uploaded_kaltura = file.uploaded_kaltura;
            if (uploaded_kaltura) {
				$('<p/>').text("File uploaded to Kaltura successfully "+file.name).appendTo('#files');
				onContributionWizardCloseTimeouted(true);

            } else {
            	$('<p/>').text("Error uploading file: "+(file.upload_kaltura_error?file.upload_kaltura_error:'')).appendTo('#files');
            }
            
        });
    },
    progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .bar').css(
            'width',
            progress + '%'
        );
    }
});
},
$('#close_popup').on('click', function (e) {
topWindow.KalturaModal.closeModal();
})
);
</script>

<?php endif; ?>
</html>