<?php
/**
 * @name Plugin  kaltura
 * @abstract Plugin to implement blogType and modify wordpress for typeAula "langBlog"
 * @author Antoni Bertran (antoni@tresipunt.com)
 * @copyright 2010 Universitat Oberta de Catalunya
 * @license GPL
 * @version 1.0.0
 * Date December 2010
*/

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'blogType.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'utils'.DIRECTORY_SEPARATOR.'UtilsPropertiesWP.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'defaultType.php');

class kaltura extends defaultType implements blogType {
	
	private $configuration = null;
	
	public function __construct() {
		//Mirem de carregar la configuracio si en te
		$this->configuration = new UtilsPropertiesWP(dirname(__FILE__).'/../configuration/'.get_class($this).'.cfg');
		
	}
	public function changeTheme() {
		
		switch_theme("langBlog2", "langBlog2"); //change theme
                   
	}
	
	public function loadPlugins() {$options = get_option('flv_options');
		$plugins = $this->removeActivedPlugins(array('menu_ajuda/menu_ajuda.php','menu_tauler/menu_tauler.php','members-only/members-only.php','wordpress-thread-comment/wp-thread-comment.php','all-in-one-video-pack/all_in_one_video_pack.php','EditPARAMSblog/EditPARAMSblog.php', 'stepsLB/LBsteps.php'));
    	activate_plugins($plugins,'');
	}
	
	public function roleMapping($role, $blti) {
		
//usertypeId = (String) it.next();^M
//                                   if ("RESPONSABLE".equals(usertypeId))^M
//                                       permiso = "editor"; ^M
//                                   if ("ADMINISTRACIO".equals(usertypeId))^M
//                                       permiso = "editor";^M
//                                   if ("PROFESSOR".equals(usertypeId))^M
//                                       permiso = "editor";^M
//                                   if ("CREADOR".equals(usertypeId))^M
//                                       permiso = "editor";^M
//                                   if ("EDITOR".equals(usertypeId))^M
//                                       permiso = "editor";^M
//                                             if (("ESTUDIANT".equals(usertypeId)) && ("GRUP".equals(domainTypeId)))  //Solo en el caso de que sea grupo es perfil editor^M
//                                                   permiso = "editor";^M
		//Cas de moodle  || $role == 'Instructor,Administrator' així es passa un admin
		if ($role == 'Administrator' || $role == 'Instructor,Administrator') return "administrator";
		if ($role == 'Instructor') {
			return "editor";
		}
		if ($role == 'Student') {
			$var_custom_domain_typeid = 'custom_domain_typeid';
			//Mirem si es de tipus GRUP per aixo usem el custom_typeid
			if (isset($blti[$var_custom_domain_typeid]) && strlen($blti[$var_custom_domain_typeid])>0) {
				if ($blti[$var_custom_domain_typeid]=='GRUP')
				  return "editor";
			}
		}
//		Esta el el UOC_Auth pero no es fa servir ja que mai es retorna tipus subscriber
//	if($UserBlogRol == "editor" && $blogType=="kaltura"){
//        $UserBlogRol ="editor";
//      }
//      if($UserBlogRol == "subscriber" && $blogType=="kaltura"){
//        $UserBlogRol ="author";
//      }
		
		return "subscriber";
	}

    /**
     * This function contains the last actions before show blog
     */
    public function postActions($obj) {

		update_option( 'permalink_structure', '' );
		$partner_id = isset($obj->context->info['custom_partner_id'])?$obj->context->info['custom_partner_id']:false;
    	$loaded_partner_id = false;
    	if ($partner_id) {
    		$configuration_file = dirname(__FILE__).'/../configuration/'.get_class($this).'_'.$obj->context->info['oauth_consumer_key'].'_'.$partner_id.'.cfg';
	    	if (file_exists($configuration_file)) {
	    		$this->configuration = new UtilsPropertiesWP($configuration_file);
	    		$loaded_partner_id = true;
	    	}
    	}
    	if (!$loaded_partner_id) {
	    	$configuration_file = dirname(__FILE__).'/../configuration/'.get_class($this).'_'.$obj->context->info['oauth_consumer_key'].'.cfg';
	    	if (file_exists($configuration_file)) {
	    		$this->configuration = new UtilsPropertiesWP($configuration_file);    		
	    	}
    	}
		
		//abertran 20120801 Added to get the plugin adminize and enable it
    	$mw_adminimize = $this->configuration->getProperty('mw_adminimize', false);
    	if ($mw_adminimize != false) {
    		activate_plugins('adminimize/adminimize.php','');
	    	$options_mw_adminimize = get_option('mw_adminimize');
//	    	$new_options_mw_adminimize = get_option( 'mw_adminimize', $mw_adminimize)
			$array_fields = array('mw_adminimize_disabled_menu_administrator_items','mw_adminimize_disabled_submenu_administrator_items','mw_adminimize_disabled_global_option_administrator_items','mw_adminimize_disabled_metaboxes_post_administrator_items','mw_adminimize_disabled_metaboxes_page_administrator_items','mw_adminimize_disabled_menu_editor_items','mw_adminimize_disabled_submenu_editor_items','mw_adminimize_disabled_global_option_editor_items','mw_adminimize_disabled_metaboxes_post_editor_items','mw_adminimize_disabled_metaboxes_page_editor_items','mw_adminimize_disabled_menu_author_items','mw_adminimize_disabled_submenu_author_items','mw_adminimize_disabled_global_option_author_items','mw_adminimize_disabled_metaboxes_post_author_items','mw_adminimize_disabled_metaboxes_page_author_items','mw_adminimize_disabled_menu_contributor_items','mw_adminimize_disabled_submenu_contributor_items','mw_adminimize_disabled_global_option_contributor_items','mw_adminimize_disabled_metaboxes_post_contributor_items','mw_adminimize_disabled_metaboxes_page_contributor_items','mw_adminimize_disabled_menu_subscriber_items','mw_adminimize_disabled_submenu_subscriber_items','mw_adminimize_disabled_global_option_subscriber_items','mw_adminimize_disabled_metaboxes_post_subscriber_items','mw_adminimize_disabled_metaboxes_page_subscriber_items','mw_adminimize_default_menu','mw_adminimize_default_submenu','_mw_adminimize_user_info','_mw_adminimize_dashmenu','_mw_adminimize_footer','_mw_adminimize_writescroll','_mw_adminimize_tb_window','_mw_adminimize_cat_full','_mw_adminimize_db_redirect','_mw_adminimize_ui_redirect','_mw_adminimize_advice','_mw_adminimize_advice_txt','_mw_adminimize_timestamp','_mw_adminimize_control_flashloader','_mw_adminimize_db_redirect_txt','mw_adminimize_disabled_link_option_administrator_items','mw_adminimize_disabled_link_option_editor_items','mw_adminimize_disabled_link_option_author_items','mw_adminimize_disabled_link_option_contributor_items','mw_adminimize_disabled_link_option_subscriber_items','_mw_adminimize_own_values','_mw_adminimize_own_options','_mw_adminimize_own_post_values','_mw_adminimize_own_post_options','_mw_adminimize_own_page_values','_mw_adminimize_own_page_options','_mw_adminimize_own_link_values','_mw_adminimize_own_link_options');

/*	    	$new_options_mw_adminimize = unserialize($mw_adminimize);
	    	var_dump($new_options_mw_adminimize);
	    	*/
	    	foreach($array_fields as $key)
	    		$options_mw_adminimize[$key] = unserialize($this->configuration->getProperty($key, 's:0:"0"'));
	    	update_option('mw_adminimize', $options_mw_adminimize);
    	}
    	//20121009 - abertranb - enable auto publish comments
    	update_option('moderation_notify',0);
    	update_option('comment_whitelist',0);
    	//END
    	 
    	
		//external_links_plugin
		/*$options_kaltura = get_option('all-in-one-video-pack');
		$options_kaltura['kaltura_partner_id']=$this->configuration->getProperty('kaltura_partner_id');
		$options_kaltura['kaltura_cms_user']=$this->configuration->getProperty('kaltura_cms_user');
		$options_kaltura['kaltura_cms_password']=$this->configuration->getProperty('kaltura_cms_password');
		$options_kaltura['kaltura_enable_video_comments']=$this->configuration->getProperty('kaltura_enable_video_comments');
		$options_kaltura['kaltura_allow_anonymous_comments']=$this->configuration->getProperty('kaltura_allow_anonymous_comments');
		$options_kaltura['kaltura_permissions_add']=$this->configuration->getProperty('kaltura_permissions_add');
		$options_kaltura['kaltura_permissions_edit']=$this->configuration->getProperty('kaltura_permissions_edit');
		$options_kaltura['kaltura_comments_player_type']=$this->configuration->getProperty('kaltura_comments_player_type');
		$options_kaltura['kaltura_default_player_type']=$this->configuration->getProperty('kaltura_default_player_type');
		$options_kaltura['kaltura_db_version']= $this->configuration->getProperty('kaltura_db_version');
		$options_kaltura['kaltura_secret']= $this->configuration->getProperty('kaltura_secret');
		$options_kaltura['kaltura_admin_secret']= $this->configuration->getProperty('kaltura_admin_secret');
		$options_kaltura['kaltura_kwc_uiconf_admin']= $this->configuration->getProperty('kaltura_kwc_uiconf_admin');
		$options_kaltura['kaltura_kcw_uiconf_comments']= $this->configuration->getProperty('kaltura_kcw_uiconf_comments');
		$options_kaltura['kaltura_server_url']= $this->configuration->getProperty('kaltura_server_url');
		$options_kaltura['kaltura_cdn_url']= $this->configuration->getProperty('kaltura_cdn_url');
		$options_kaltura['kaltura_anonymous_user_id']= $this->configuration->getProperty('kaltura_anonymous_user_id');
		$options_kaltura['kaltura_kse_uiconf']= $this->configuration->getProperty('kaltura_kse_uiconf');
		$options_kaltura['kaltura_kcw_uiconf']= $this->configuration->getProperty('kaltura_kcw_uiconf');
		$options_kaltura['kaltura_kcw_uiconf_for_se']= $this->configuration->getProperty('kaltura_kcw_uiconf_for_se');
		$options_kaltura['kaltura_thumbnail_uiconf']= $this->configuration->getProperty('kaltura_thumbnail_uiconf');
		$options_kaltura['kaltura_logger']= $this->configuration->getProperty('kaltura_logger');
		
		update_option('all-in-one-video-pack', $options_kaltura);				*/
		//Nova versio
		update_option('kaltura_partner_id', $this->configuration->getProperty('kaltura_partner_id'));
		update_option('kaltura_cms_user', $this->configuration->getProperty('kaltura_cms_user'));
		update_option('kaltura_cms_password', $this->configuration->getProperty('kaltura_cms_password'));
		update_option('kaltura_enable_video_comments', $this->configuration->getProperty('kaltura_enable_video_comments'));
		update_option('kaltura_allow_anonymous_comments', $this->configuration->getProperty('kaltura_allow_anonymous_comments'));
		update_option('kaltura_permissions_add', $this->configuration->getProperty('kaltura_permissions_add'));
		update_option('kaltura_permissions_edit', $this->configuration->getProperty('kaltura_permissions_edit'));
		update_option('kaltura_comments_player_type', $this->configuration->getProperty('kaltura_comments_player_type'));
		update_option('kaltura_default_player_type', $this->configuration->getProperty('kaltura_default_player_type'));
		update_option('kaltura_db_version', $this->configuration->getProperty('kaltura_db_version'));
		update_option('kaltura_secret', $this->configuration->getProperty('kaltura_secret'));
		update_option('kaltura_admin_secret', $this->configuration->getProperty('kaltura_admin_secret'));
		update_option('kaltura_kwc_uiconf_admin', $this->configuration->getProperty('kaltura_kwc_uiconf_admin'));
		update_option('kaltura_kcw_uiconf_comments', $this->configuration->getProperty('kaltura_kcw_uiconf_comments'));
		update_option('kaltura_server_url', $this->configuration->getProperty('kaltura_server_url'));
		update_option('kaltura_cdn_url', $this->configuration->getProperty('kaltura_cdn_url'));
		update_option('kaltura_anonymous_user_id', $this->configuration->getProperty('kaltura_anonymous_user_id'));
		update_option('kaltura_kse_uiconf', $this->configuration->getProperty('kaltura_kse_uiconf'));
		update_option('kaltura_kcw_uiconf', $this->configuration->getProperty('kaltura_kcw_uiconf'));
		update_option('kaltura_kcw_uiconf_for_se', $this->configuration->getProperty('kaltura_kcw_uiconf_for_se'));
		update_option('kaltura_thumbnail_uiconf', $this->configuration->getProperty('kaltura_thumbnail_uiconf'));
		update_option('kaltura_logger', $this->configuration->getProperty('kaltura_logger'));
	
		//logout redirect plugin
		$members_only_opt = get_option('members_only_options');
		$members_only_opt['members_only']=TRUE;
		$members_only_opt['redirect']=FALSE;
		$members_only_opt['redirect_to']='specifypage';
		$members_only_opt['redirect_url']='login/index.php';
		$members_only_opt['feedkey_reset']=FALSE;
		update_option('members_only_options', $members_only_opt);

    }
}
