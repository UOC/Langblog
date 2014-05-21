<?php
/*
This file handles the admin area and functions.
You can use this file to make changes to the
dashboard.
*/


/************* CUSTOM LOGIN PAGE *****************/

// calling your own login css so you can style it
function langblogR_login_css() {
	wp_enqueue_style( 'langblogR_login_css', get_template_directory_uri() . '/library/css/login.css', false );
}

// changing the logo link from wordpress.org to your site
function langblogR_login_url() {  return home_url(); }

// changing the alt text on the logo to show your site name
function langblogR_login_title() { return get_option('blogname'); }

// calling it only on the login page
add_action('login_enqueue_scripts', 'langblogR_login_css', 10);
add_filter('login_headerurl', 'langblogR_login_url');
add_filter('login_headertitle', 'langblogR_login_title');

?>
