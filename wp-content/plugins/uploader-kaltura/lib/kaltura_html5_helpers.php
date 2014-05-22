<?php
use uploader_kaltura\API as KalturaBaseAPI;
class KalturaHTML5Helpers
{
	private static $is_all_in_one_is_2_5_or_newer = null;

	/**
	 * get if newer than 2.5
	 * @return [type] [description]
	 */
	public static function all_in_one_is_2_5_or_newer(){
		if (self::$is_all_in_one_is_2_5_or_newer == null){
			$file = dirname(__FILE__).'/../../all-in-one-video-pack/all_in_one_video_pack.php';
			if (!file_exists($file)) {
				$file = dirname(__FILE__).'/../../all-in-one-video-pack_/all_in_one_video_pack.php';
			}
			$plugin_data = get_plugin_data( $file, false, false);
			$versions = array('2.4.9', $plugin_data['Version']);
			usort($versions, 'version_compare');
			self::$is_all_in_one_is_2_5_or_newer = $versions[0]!=$plugin_data['Version'];
		}
		return self::$is_all_in_one_is_2_5_or_newer;
	}  

	/**
	 * Get an option depending of all-in-one-video-pack version  
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	public function getOption($option){
		return (self::all_in_one_is_2_5_or_newer()?KalturaHelpers::getOption($option):get_option($option));
	}

    function getKalturaConfiguration() 
    {
    	$config = new KalturaConfiguration(self::all_in_one_is_2_5_or_newer()?self::getOption('kaltura_partner_id'):get_option('kaltura_partner_id'));
    	$config->serviceUrl = self::getServerUrl();
    	require_once("kaltura_wordpress_logger.php");
    	$config->setLogger(new KalturaWordpressLogger());
    	return $config;
    }
    
    function getServerUrl() 
    {
    	$url = self::getOption(self::all_in_one_is_2_5_or_newer()?'server_url':'kaltura_server_url');
    
    	// remove the last slash from the url
    	if (substr($url, strlen($url) - 1, 1) == '/')
    		$url = substr($url, 0, strlen($url) - 1);
    		
    	return $url;
    }
    
   
    function getCdnUrl() 
    {

    	$url = self::getOption(self::all_in_one_is_2_5_or_newer()?'cdn_url':'kaltura_cdn_url');
    	
    	// remove the last slash from the url
    	if (substr($url, strlen($url) - 1, 1) == '/')
    		$url = substr($url, 0, strlen($url) - 1);
    		
    	return $url;
    }
    
    function getLoggedUserId() 
    {
    	global $user_ID, $user_identity;
    	
    	if (!$user_ID) 
    		return self::getOption('anonymous_user_id');
    	else
        	return $user_ID;
    }
    
    function getPluginUrl() 
    {
    	$plugin_name = plugin_basename(__FILE__);   
    	$indx = strpos($plugin_name, "/");
    	$plugin_dir = substr($plugin_name, 0, $indx);
    	$site_url = get_settings('siteurl');
    	
    	// site url can be http, but the admin part can run under https
    	if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')
    		$site_url = str_replace('http://', 'https://', $site_url);
    	
    	$plugin_url = $site_url . '/wp-content/plugins/' . $plugin_dir;
    	return $plugin_url;
    }
    
    function generateTabUrl($params) 
    {
    	$query = $_SERVER["REQUEST_URI"];
    	foreach($_GET as $k => $v)
    		$query = add_query_arg($k, false, $query);
    		
    	$query = add_query_arg($params, $query);
    	return $query;
    }
    
    function getRequestUrl()
    {
    	return $_SERVER["REQUEST_URI"];
    }

	function getContributionWizardFlashVars($ks, $entryId = null)
	{
		$flashVars = array();
		$flashVars["userId"] 		= self::getLoggedUserId();
		$flashVars["sessionId"] 	= $ks;
		$flashVars["partnerId"] 	= self::getOption("kaltura_partner_id");
		$flashVars["subPartnerId"] 	= self::getOption("kaltura_partner_id") * 100;
		$flashVars["afterAddentry"] = "onContributionWizardAfterAddEntry";
		$flashVars["close"] 		= "onContributionWizardClose";
		$flashVars["termsOfUse"] 	= "http://corp.kaltura.com/static/tandc" ;
		$flashVars["jsDelegate"] 		= "callbacksObj";
		
		return $flashVars;
	}
	
	function getSimpleEditorFlashVars($ks, $entryId)
	{
		$flashVars = array();
		$flashVars["entryId"] 		= $entryId;
		$flashVars["kshowId"] 		= "entry-".$entryId;
		$flashVars["partnerId"] 	= self::getOption("kaltura_partner_id");
		$flashVars["subpId"] 		= self::getOption("kaltura_partner_id") * 100;
		$flashVars["uid"] 		    = self::getLoggedUserId();
		$flashVars["ks"] 			= $ks;
		$flashVars["backF"] 		= "onSimpleEditorBackClick";
		$flashVars["saveF"] 		= "onSimpleEditorSaveClick";
		$flashVars["jsDelegate"] 		= "callbacksObj";
		
		return $flashVars;
	}
	
	function getKalturaPlayerFlashVars($uiConfId = null, $ks = null, $entryId = null)
	{
		$flashVars = array();
		$flashVars["partnerId"] 	= self::getOption("kaltura_partner_id");
		$flashVars["subpId"] 		= self::getOption("kaltura_partner_id") * 100;
		$flashVars["uid"] 		    = self::getLoggedUserId();
		
		if ($ks)
		    $flashVars["ks"] 		= $ks;
	    if ($uiConfId)
	        $flashVars["uiConfId"] 	= $ks;
        if ($entryId)
            $flashVars["entryId"] 	= $entryId;
		
		return $flashVars;
	}
	
	function flashVarsToString($flashVars)
	{
		$flashVarsStr = "";
		foreach($flashVars as $key => $value)
		{
			$flashVarsStr .= ($key . "=" . $value . "&"); 
		}
		return substr($flashVarsStr, 0, strlen($flashVarsStr) - 1);
	}
	
	function getSwfUrlForWidget($widgetId = null, $uiConfId = null)
	{
	    if (!$widgetId)
	        $widgetId = "_" . self::getOption("kaltura_partner_id");
	        
	    $url = self::getServerUrl() . "/index.php/kwidget/wid/" . $widgetId;
	    if ($uiConfId)
	        $url .= ("/ui_conf_id/" . $uiConfId);
	        
		return $url;
	}
	
	function getContributionWizardUrl($uiConfId)
	{
		//return self::getServerUrl() . "/kse/ui_conf_id/" . $uiConfId; bug Kaltura
		return self::getServerUrl() . "/kcw/ui_conf_id/" . $uiConfId;
	}
	
	function getSimpleEditorUrl($uiConfId)
	{
		return self::getServerUrl() . "/kae/ui_conf_id/" . $uiConfId;
	}

	function userCanEdit($override = null) {
		global $current_user;

		$roles = array();
		foreach($current_user->roles as $key => $val)
			$roles[$val] = 1;
			 
		if ($override === null) 
			$permissionsEdit = self::getOption('kaltura_permissions_edit');
		else
			$permissionsEdit = $override;
		// note - there are no breaks in the switch (code should jump to next case)
		switch($permissionsEdit)
		{
			case "0":
				return true;
			case "1":
				if (@$roles["subscriber"])
					return true;
			case "2":
				if (@$roles["editor"])
					return true;
				else if (@$roles["author"])
					return true;
				else if (@$roles["contributor"])
					return true;
			case "3":
				if (@$roles["administrator"])
					return true;
		}
		
		return false;
	}

	function userCanAdd($override = null) {
		global $current_user;
		
		$roles = array();
		foreach($current_user->roles as $key => $val)
			$roles[$val] = 1;
		
		if ($override === null)
			$permissionsAdd = self::getOption('kaltura_permissions_add');
		else
			$permissionsAdd = $override;
			
		// note - there are no breaks in the switch (code should jump to next case)
		switch($permissionsAdd)
		{
			case "0":
				return true;
			case "1":
				if (@$roles["subscriber"])
					return true;
			case "2":
				if (@$roles["editor"])
					return true;
				else if (@$roles["author"])
					return true;
				else if (@$roles["contributor"])
					return true;
			case "3":
				if (@$roles["administrator"])
					return true;
		}
		return false;
	}

	function anonymousCommentsAllowed()
	{
		return self::getOption("kaltura_allow_anonymous_comments") == true ? true : false;
	}
	
	function videoCommentsEnabled()
	{
		return self::getOption("kaltura_enable_video_comments") == true ? true : false;
	}
	
	function getThumbnailUrl($widgetId = null, $entryId = null, $width = 240, $height= 180, $version = 100000)
	{
		$config = self::getKalturaConfiguration();
		$url = self::getCdnUrl();
		$url .= "/p/" . self::getOption("kaltura_partner_id");
		$url .= "/sp/" . self::getOption("kaltura_partner_id")*100;
		$url .= "/thumbnail";
		if ($widgetId)
			$url .= "/widget_id/" . $widgetId;
		else if ($entryId)
			$url .= "/entry_id/" . $entryId;
		$url .= "/width/" . $width;
		$url .= "/height/" . $height;
		$url .= "/type/2";
		$url .= "/bgcolor/000000"; 
		if ($version !== null)
			$url .= "/version/" . $version;
		return $url;
	}
	
	function getCommentPlaceholderThumbnailUrl($widgetId = null, $entryId = null, $width = 240, $height= 180, $version = 100000)
	{
		$url = self::getThumbnailUrl($widgetId, $entryId, $width, $height, $version);
		$url .= "/crop_provider/wordpress_comment_placeholder";
		return $url;
	}

	function compareWPVersion($compareVersion, $operator)
	{
		global $wp_version;
		
		return version_compare($wp_version, $compareVersion, $operator);
	}
	
    function compareKalturaVersion($compareVersion, $operator)
	{
		$kversion = kaltura_get_version;
		
		return version_compare($kversion, $compareVersion, $operator);
	}
	
	function addWPVersionJS()
	{
		global $wp_version;
		echo("<script type='text/javascript'>\n");
		echo('var Kaltura_WPVersion = "' . $wp_version . '";'."\n");
		echo('var Kaltura_PluginUrl = "' . self::getPluginUrl() . '";'."\n");
		echo("</script>\n");
	}
	
	function calculatePlayerHeight($uiConfId, $width, $playerRatio = '4:3')
	{
		$kmodel = KalturaModel::getInstance();
		$player = $kmodel->getPlayerUiConf($uiConfId);
		
		$spacer = $player->height - ($player->width / 4) * 3; // assume the width and height saved in kaltura is 4/3
		if ($playerRatio == '16:9')
			$height = ($width / 16) * 9 + $spacer;
		else
			$height = ($width / 4) * 3 + $spacer;
		
		return (int)$height;
	}
	
	function runKalturaShortcode($content, $callback)
	{
		global $shortcode_tags;
		
		// we will backup the shortcode array, and run only our shortcode
		$shortcode_tags_backup = $shortcode_tags;
		
		add_shortcode('kaltura-widget', $callback);
			
		$content = do_shortcode($content);
		
		// now we can restore the original shortcode list
		$shortcode_tags = $shortcode_tags_backup;
	}
	
	function dieWithConnectionErrorMsg($errorDesc)
	{
		echo '
		<div class="error">
			<p>
				<strong>Your connection has failed to reach the Kaltura servers. Please check if your web host blocks outgoing connections and then retry.</strong> ('.$errorDesc.')
			</p>
		</div>';
		die();
	}
	
	function getCloseLinkForModals()
	{
		return '<a href="#" onclick="((window.opener) ? window.opener : (window.parent) ? window.parent : window.top).KalturaModal.closeModal();">'.__('Close').'</a>';
	}
	
	/**
	 * sometimes wordpress thinks our url is a permalink and sets 404 header, calling this function will force back to 200
	 */
	function force200Header()
	{
		status_header(200);
	}

	/*** provided a kaltura session client and parameters for a media entry adds the entry ***/
/*** returns the new entry id, and the status parameter is passed by reference so ***/
/*** just uploaded status is also available after the function is called. ***/
/*** params are kaltura client with session, file and path, name for entry, tags. ***/
function addEntry($client,$filePath,$entryName,$tags,&$status)
{
	$token = $client->media->upload($filePath);
	$entry = new KalturaMediaEntry();
	$entry->name = $entryName; //"my media type entry";
	// do call to find out if media is audio or video
	if (self::isAudio(self::fileExtension($filePath->filePath)))
	{
		//$entry->mediaType = KalturaMediaType::AUDIO;
		$entry->mediaType = KalturaMediaType_AUDIO;
		$entry->conversionProfileId = 2657521; // profile id copied from UI of KMC yours will be different
	} else {
		//$entry->mediaType = KalturaMediaType::VIDEO;
		$entry->mediaType = KalturaMediaType_VIDEO;
		$entry->conversionProfileId = 2642471; // profile id copied from UI of KMC yours will be different
	}
	// using default for now could provide as asset in media space. Logo or Audio or...
	//$entry->thumbnailUrl = "http://some-url-of-yours/My_Icon.jpg";
	$entry->tags = $tags;
	$newEntry = $client->media->addFromUploadedFile($entry, $token);
	$entryId = $newEntry->id;
	$status = $newEntry->status;
	return $entryId;
} // add Entry
	function isAudio($ext)
	{
		$isAudio=FALSE;
		switch($ext)
		{
			case 'mp3':
			$isAudio=TRUE;
			break;
			case 'wma':
			$isAudio=TRUE;
			break;
			case 'ra':
			$isAudio=TRUE;
			break;
			default:
			$isAudio=FALSE;
		} // end switch($ext)
		return $isAudio;
	} // is Audio
	function fileExtension($filePath)
	{
		$m = explode(".", $filePath);
		$size = (sizeof($m)-1);
		$ext = strtolower($m[$size]);
		return $ext;
	}
}
?>