<?php

	$flashVarsStr = KalturaHelpers::flashVarsToString($viewData["flashVars"]);
	$flashVarsStr .= "&showCloseButton=true&showcategories=false&showtags=false&showdescription=false";	
	
	if(function_exists('load_plugin_textdomain')) {
		load_plugin_textdomain('all-in-one-video-pack',PLUGINDIR.'/all-in-one-video-pack');
	}
?>


<!--div id="alertKaltura" style="color: rgb(194, 9, 9);background-color: rgb(192, 192, 192);text-align: center;margin-top: -12px;height: 16px;font-family: Verdana;font-size: 12px;">
		<p><?php echo __("textAlertRecord","all-in-one-video-pack");?></p>
</div-->

<div id="kaltura_contribution_wizard_wrapper"></div>

<script type="text/javascript">
	var cwWidth = 680;
	var cwHeight = 360;
	
	var topWindow = Kaltura.getTopWindow();
	// fix for IE6, scroll the page up so modal would animate in the center of the window
	if (jQuery.browser.msie && jQuery.browser.version < 7)
		topWindow.scrollTo(0,0);

	var cwSwf = new SWFObject("<?php echo $viewData["swfUrl"]; ?>", "kaltura_contribution_wizard", cwWidth, cwHeight, "9", "#000000");
	cwSwf.addParam("flashVars", "<?php echo $flashVarsStr; ?>");
	cwSwf.addParam("allowScriptAccess", "always");
	cwSwf.addParam("allowNetworking", "all");
</script>
