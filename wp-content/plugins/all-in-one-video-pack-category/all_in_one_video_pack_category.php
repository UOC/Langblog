<?php
/**
 * All in one configuration video pack category
 * @author Antoni Bertran (abertranb@uoc.edu)
 * @copyright 2013 Universitat Oberta de Catalunya
 * @package all-in-one-video-pack-category
 * @version 27: all_in_one_video_pack_category.php 2013-11-04 09:15:09Z abertran $
 * @license GPL
 * Date November 2013
 */
/*
Plugin Name: all-in-one-video-pack-category
Plugin URI: http://www.uoc.edu/
Description: Allows to manage categories of your blog, based on all-in-one-video-pack from Kaltura
Version: 1.0.0
Author: Uoc
Author URI: http://www.uoc.edu/
License: GPLv2 or later
*/
session_start();
require_once (ABSPATH . WPINC . '/pluggable.php');
//require_once(dirname(__FILE__).'/settings.php');
require_once (dirname(__FILE__) . '/lib/kaltura_category_helpers.php');
add_action('init', 'loadInitKalturaCategory');
function loadInitKalturaCategory() 
{
    if (function_exists('load_plugin_textdomain')) 
    {
        load_plugin_textdomain('all-in-one-video-pack-category', false, '/all-in-one-video-pack-category/lang');
    }
}
add_action('admin_menu', 'kaltura_category_add_admin_menu'); // add kaltura admin menu
/*
 * Occures when publishing the post, and on every save while the post is published
 *
 * @param $postId
 * @param $post
 * @return unknown_type
*/
function kaltura_category_publish_post($post_id, $post) 
{
    //require_once("lib/kaltura_wp_model.php");
    $content = $post->post_content;
    $shortcode_tags = array();
    $idEntry = $post->post_content;
    $idEntry = explode('entryid="', $idEntry);
    $idEntry = explode('"', $idEntry[1]);
    $idEntry = $idEntry[0];
    global $current_user;
    get_currentuserinfo();
    try
    {
        KalturaCategoryHelpers::register($idEntry, $post->post_date, $current_user->display_name);
    }
    catch(Exception $e) 
    {
        error_log("Error setting categories of post");
        error_log(serialize($e));
    }
}
add_action("publish_post", "kaltura_category_publish_post", 10, 2);
add_action("publish_page", "kaltura_category_publish_post", 10, 2);
/*
 * Occured when posting a comment
 * @param $comment_id
 * @param $approved
 * @return unknown_type
*/
function kaltura_category_comment_post($comment_id, $approved) 
{
    if ($approved) 
    {
        global $kaltura_comment_id;
        $kaltura_comment_id = $comment_id;
        $comment = get_comment($comment_id);
        $idEntry = $comment->comment_content;
        $idEntry = explode('entryid="', $idEntry);
        $idEntry = explode('"', $idEntry[1]);
        $idEntry = $idEntry[0];
        try
        {
            KalturaCategoryHelpers::register($idEntry, $comment->comment_date, $comment->comment_author);
        }
        catch(Exception $e) 
        {
            error_log("Error setting categories of comment post");
            error_log(serialize($e));
        }
    }
}
add_action("comment_post", "kaltura_category_comment_post", 10, 2);
function kaltura_category_admin_page() 
{
    //require_once("lib/kaltura_model.php");
    require_once ('admin/kaltura_admin_controller.php');
}
function kaltura_category_network_pages() 
{
    add_submenu_page('settings.php', __('All in One Video Tree Categories', 'all-in-one-video-pack-category') , __('All in One Video Tree Categories', 'all-in-one-video-pack-category') , 'manage_options', 'kaltura_category_admin_page', 'kaltura_category_admin_page');
}
//add_action( 'network_admin_menu', 'kaltura_category_network_pages' );
function kaltura_category_add_admin_menu() 
{
    add_options_page(__('All in One Video Tree Categories', 'all-in-one-video-pack-category') , __('All in One Video Tree Categories', 'all-in-one-video-pack-category') , 8, 'interactive_video_category', 'kaltura_category_admin_page');
}
function kaltura_category_get_version() 
{
    $plugin_data = implode('', file(str_replace('all_in_one_video_pack_category.php', 'interactive_video_category.php', __FILE__)));
    if (preg_match("|Version:(.*)|i", $plugin_data, $version)) $version = trim($version[1]);
    else $version = '';
    
    return $version;
}
