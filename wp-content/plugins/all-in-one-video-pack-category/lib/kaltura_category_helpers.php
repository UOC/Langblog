<?php
/**
 * All in one configuration video pack category
 * @author Antoni Bertran (abertranb@uoc.edu)
 * @copyright 2010 Universitat Oberta de Catalunya
 * @package all-in-one-video-pack-category.admin
 * @version 27: kaltura_admin_controller.php 2013-11-04 09:15:09Z abertran $
 * @license GPL
 * Date November 2013
 */
require_once(dirname(__FILE__).'/../API/KalturaClient.php');
use all_in_one_video_pack_category\API as KalturaBaseAPI;
use all_in_one_video_pack_category\API\KalturaPlugins as KalturaPlugins;
class KalturaCategoryHelpers
{

    private static $_is_all_in_one_is_2_5_or_newer = null;

    /**
     * get if newer than 2.5
     * @return [type] [description]
     */
    public static function all_in_one_is_2_5_or_newer(){
        if (self::$_is_all_in_one_is_2_5_or_newer == null){
            $plugin_data = get_plugin_data( dirname(__FILE__).'/../../all-in-one-video-pack/all_in_one_video_pack.php', false, false);
            $versions = array('2.4.9', $plugin_data['Version']);
            usort($versions, 'version_compare');
            self::$_is_all_in_one_is_2_5_or_newer = $versions[0]!=$plugin_data['Version'];
        }
        return self::$_is_all_in_one_is_2_5_or_newer;
    }  

    /**
     * Get an option depending of all-in-one-video-pack version  
     * @param  [type] $option [description]
     * @return [type]         [description]
     */
    public function getOption($option){
        return (self::all_in_one_is_2_5_or_newer()?KalturaHelpers::getOption($option):get_option($option));
    }
    
    /**
     * get the value of field
     * @param  [type] $type         [description]
     * @param  string $custom_value [description]
     * @param  [type] $date         [description]
     * @param  [type] $author       [description]
     * @return [type]               [description]
     */
    private function getValue($type, $custom_value='', $date, $author) {
        $value = false;
        global $blog_id;
        switch ($type) {
            case 'post_id':
                $value = get_the_ID();
                break;
            case 'post_name':
                $post_id = get_the_ID();
                $value = get_the_title($post_id);
                break;
            case 'blog_name':
                $value = get_bloginfo( 'name' );
                break;
            case 'blog_id':
                $value = $blog_id;
                break;
            case 'blog_id_plus_name':
                $value = $blog_id.' - '.get_bloginfo( 'name' );
                break;
            case 'name_theme':
                $value = get_template();
                break;
            case 'year':
                $value = mysql2date('Y',$date);
                break;
            case 'month':
                $value = mysql2date('M',$date);
                break;
            case 'author':
                $value = $author;
                break;
            case 'custom':
                $value = $custom_value;
                break;
        }
        return $value;
    }
    
    /**
     * Stores the category configuration
     * @param  [type] $idEntry [description]
     * @param  [type] $date    [description]
     * @param  string $author  [description]
     * @return [type]          [description]
     */
    function register($idEntry, $date, $author='') {
        $is_ok = true;
		if($idEntry!=null){
            $AllInOneCategoryTree = get_option('all-in-one-category-total-number', 0);
            if ($AllInOneCategoryTree>0) {

                $array_tree_categories = array ();
                $total_real = 0;
                
                $old_value = false;
                for ($i=0; $i<$AllInOneCategoryTree; $i++) {
                    $type = get_option('all-in-one-category-cat-'.$i, false);
                    $custom_value = get_option('all-in-one-category-cat-custom-'.$i, false);
                    $value = KalturaCategoryHelpers::getValue($type, $custom_value, $date, $author);
                    if ($value) {
                        $array_tree_categories[] = $value;
                        $total_real++;
                    }
                }

                $type = KalturaBaseAPI\KalturaSessionType::ADMIN;

                $expiry = null;
                $privileges = null;
                $categoryEntry = null;
                $pIdSem = null;
                $pIdBlog = null;
                $foundSem=false;
                $foundBlog=false;
                $foundType=false;
                $adminSecret = KalturaCategoryHelpers::getOption ('kaltura_admin_secret');
                $partnerId = KalturaCategoryHelpers::getOption('kaltura_partner_id');
                if ($adminSecret && $partnerId) {
                    $config = new KalturaBaseAPI\KalturaConfiguration($partnerId);
                    $config->serviceUrl = KalturaCategoryHelpers::getOption('kaltura_server_url');
                    $client = new KalturaBaseAPI\KalturaClient($config);
                    $userId = KalturaCategoryHelpers::getLoggedUserId();

                    $resultKs = $client->session->start($adminSecret, $userId, $type, $partnerId, $expiry, $privileges);
                    $client->setKs($resultKs);

                    $is_ok = KalturaCategoryHelpers::createCategories($array_tree_categories, $idEntry, $client);
                }
                else {
                    $is_ok = false;
                }
            }
        
		}

	}

    /**
     * get if user is logged
     * @return [type] [description]
     */
    function getLoggedUserId() 
    {
        global $user_ID, $user_identity;
        
        if (!$user_ID) 
            return KALTURA_ANONYMOUS_USER_ID; 
        else
            return $user_ID;
    }

    /**
     * Create Kaltura categories 
     * @param  [type]  $array           [description]
     * @param  [type]  $entryId         [description]
     * @param  [type]  $client          [description]
     * @param  boolean $parent_category [description]
     * @return [type]                   [description]
     */
    function createCategories($array, $entryId, $client, $parent_category=false){
        $results = false;
        try {
            $category = new KalturaBaseAPI\KalturaCategory();
            $last_category = false;
            foreach ($array as $value) {
                    $filter = new KalturaBaseAPI\KalturaCategoryFilter();
                    if ($parent_category) {
                        $filter->parentIdEqual = $parent_category;
                    }
                    $filter->fullNameEqual = $value;
                    $pager = null;
                    $results = $client->category->listAction($filter, $pager);
                    $results = $results->objects;
                    $found = false;
                    foreach($results as $obj){
                        $results = $client->category->get($obj->id);
                        if($results->name == $value){ 
                            $last_category = $results;
                            $pId = $results->id;
                            $parent_category = $pId;
                            $found=true;
                            break;
                        }
                    }
                    if (!$found){
                        $filter = new KalturaBaseAPI\KalturaCategoryFilter();
                        if ($parent_category) {
                            $filter->parentIdEqual = $parent_category;
                        }
                        $pager = null;
                        $results = $client->category->listAction($filter, $pager);
                        $results = $results->objects;
                        $found = false;
                        foreach($results as $obj){
                            $results = $client->category->get($obj->id);
                            if($results->name == $value){ 
                                $last_category = $results;
                                $pId = $results->id;
                                $parent_category = $pId;
                                $found=true;
                                break;
                            }
                        }
                        
                    }
                    if (!$found){
                        if ($parent_category)
                            $category->parentId = $parent_category;
                        $category->name = $value;
                        $results = $client->category->add($category);
                        $pId = $client->category->get($results->id);
                        $pId = $pId->id;
                        $parent_category = $pId;
                        $last_category = $results;

                    }
            }
            if ($last_category) {
                $categoryEntry = new KalturaBaseAPI\KalturaCategoryEntry();
                $categoryEntry->categoryId = $last_category->id;
                $categoryEntry->entryId = $entryId;
                $results = $client->categoryEntry->add($categoryEntry);
            }

        } catch (Exception $ex)  
        {  
            if ($ex->getCode()!='CATEGORY_ENTRY_ALREADY_EXISTS') {
                 error_log($ex->getMessage());
                 $results = false;
            }
        }
        return $results;
    }

    /**
     * get the plugin URL
     * @return [type] [description]
     */
    function getPluginUrl() 
    {
    	$plugin_name = plugin_basename(__FILE__);   
    	$indx = strpos($plugin_name, '/');
    	$plugin_dir = substr($plugin_name, 0, $indx);
    	$site_url = get_settings('siteurl');
    	
    	// site url can be http, but the admin part can run under https
    	if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')
    		$site_url = str_replace('http://', 'https://', $site_url);
    	
    	$plugin_url = $site_url . '/wp-content/plugins/' . $plugin_dir;
    	return $plugin_url;
    }
    
	/**
	 * sometimes wordpress thinks our url is a permalink and sets 404 header, calling this function will force back to 200
	 */
	function force200Header()
	{
		status_header(200);
	}
}

if (KalturaCategoryHelpers::all_in_one_is_2_5_or_newer() && !class_exists('KalturaCategoryListResponse')){
    class KalturaCategoryListResponse
    {

    }
}
?>