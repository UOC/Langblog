<?php

	//Canvi Antoni (abertranb) 20120607 perqu� agafi la configuraci� de les opcions
	define("KALTURA_SERVER_URL", get_option("kaltura_server_url")?get_option("kaltura_server_url"):"http://www.kaltura.com");
	//define("KALTURA_SERVER_URL", "http://www.kaltura.com");
	define("KALTURA_CDN_URL", get_option("kaltura_cdn_url")?get_option("kaltura_cdn_url"):"http://cdn.kaltura.com");
	//define("KALTURA_CDN_URL", "http://cdn.kaltura.com");
	define("KALTURA_KCW_UICONF_ADMIN",  get_option("kaltura_kwc_uiconf_admin")?get_option("kaltura_kwc_uiconf_admin"):9191921);
	//define("KALTURA_KCW_UICONF_ADMIN", 7589961);
	define("KALTURA_KCW_UICONF_COMMENTS", get_option("kaltura_kcw_uiconf_comments")?get_option("kaltura_kcw_uiconf_comments"):9191921);
	//define("KALTURA_KCW_UICONF_COMMENTS", 7589961);

	define("KALTURA_ANONYMOUS_USER_ID", get_option("kaltura_anonymous_user_id")?get_option("kaltura_anonymous_user_id"):"");
	//define("KALTURA_ANONYMOUS_USER_ID", "Anonymous");
	define("KALTURA_KSE_UICONF", get_option("kaltura_kse_uiconf")?get_option("kaltura_kse_uiconf"):540);
	//define("KALTURA_KSE_UICONF", 540);
	define("KALTURA_KCW_UICONF", get_option("kaltura_kcw_uiconf")?get_option("kaltura_kcw_uiconf"):542);
	//define("KALTURA_KCW_UICONF", 542);
	define("KALTURA_KCW_UICONF_FOR_SE", get_option("kaltura_kcw_uiconf_for_se")?get_option("kaltura_kcw_uiconf_for_se"):541);
	//define("KALTURA_KCW_UICONF_FOR_SE", 541);
	define("KALTURA_THUMBNAIL_UICONF", get_option("kaltura_thumbnail_uiconf")?get_option("kaltura_thumbnail_uiconf"):533);
	//define("KALTURA_THUMBNAIL_UICONF", 533);
	// Amb les constants booleanes i el get_option hem d'anar amb compte
	define("KALTURA_LOGGER", (get_option("kaltura_logger") && get_option("kaltura_logger")=="true") ?  true : false);
	//define("KALTURA_LOGGER", false);
	
	

/*	define("KALTURA_SERVER_URL", "http://www.kaltura.com");
	define("KALTURA_CDN_URL", "http://cdn.kaltura.com");
	define("KALTURA_ANONYMOUS_USER_ID", "Anonymous");
	define("KALTURA_KSE_UICONF", 540);
	define("KALTURA_KCW_UICONF", 542);
	
	
	
	define("KALTURA_KCW_UICONF_ADMIN", 7589961);
	define("KALTURA_KCW_UICONF_COMMENTS", 7589961);
	//define("KALTURA_KCW_UICONF_ADMIN", 2968311);
	//define("KALTURA_KCW_UICONF_COMMENTS", 2968311);

	define("KALTURA_KCW_UICONF_FOR_SE", 541);
	define("KALTURA_THUMBNAIL_UICONF", 533);
	define("KALTURA_LOGGER", false);*/
	
	$KALTURA_DEFAULT_PLAYERS = array (
		array(
			"id" => 534,
			"name" => "Light Skin", 
			"width" => 400,
			"height" => 330
		),
		array(
			"id" => 535,
			"name" => "Dark Skin", 
			"width" => 400,
			"height" => 330
		),
	);
	
	
	$KALTURA_LEGACY_PLAYERS = array ();
?>