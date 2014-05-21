<?php
/*
This is the core file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.
*/

/*********************
LAUNCH THEME
Let's fire off all the functions
and tools. I put it up here so it's
right up top and clean.
*********************/

// we're firing all out initial functions at the start
add_action('after_setup_theme','langblogR_ahoy', 16);

function langblogR_ahoy() {

    // launching operation cleanup
    add_action('init', 'langblogR_head_cleanup');
    // A better title
    add_filter( 'wp_title', 'langblogR_title', 10, 3 );
    // remove WP version from RSS
    add_filter('the_generator', 'langblogR_rss_version');
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', 'langblogR_remove_wp_widget_recent_comments_style', 1 );
    // clean up comment styles in the head
    add_action('wp_head', 'langblogR_remove_recent_comments_style', 1);
    // clean up gallery output in wp
    add_filter('gallery_style', 'langblogR_gallery_style');

    // enqueue base scripts and styles
    add_action('wp_enqueue_scripts', 'langblogR_scripts_and_styles', 999);
    // ie conditional wrapper

    // launching this stuff after theme setup
    langblogR_theme_support();

    // cleaning up random code around images
    add_filter('the_content', 'langblogR_filter_ptags_on_images');
    // cleaning up excerpt
    //add_filter('excerpt_more', 'langblogR_excerpt_more');

} /* end langblogR ahoy */

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function langblogR_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	//remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	//remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	//remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
  // remove WP version from css
  add_filter( 'style_loader_src', 'langblogR_remove_wp_ver_css_js', 9999 );
  // remove Wp version from scripts
  add_filter( 'script_loader_src', 'langblogR_remove_wp_ver_css_js', 9999 );

  add_filter('wp_generate_tag_cloud', 'langblogR_remove_tag_cloud_style',10,3);

} /* end langblogR head cleanup */



// A better title
// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function langblogR_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'langblogR' ), max( $paged, $page ) );
  }

  return $title;

} // end better title


// remove WP version from RSS
function langblogR_rss_version() { return ''; }

// remove WP version from scripts
function langblogR_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

// remove injected CSS for recent comments widget
function langblogR_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}

// remove injected CSS from recent comments widget
function langblogR_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

function langblogR_remove_tag_cloud_style($tag_string){
   return preg_replace("/style='font-size:.+pt;'/", '', $tag_string);
}

// remove injected CSS from gallery
function langblogR_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function langblogR_scripts_and_styles() {
  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
  if (!is_admin()) {

    // modernizr (without media query polyfill)
    wp_register_script( 'langblogR-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

    // register main stylesheet
    wp_register_style( 'langblogR-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );

    // ie-only style sheet
    wp_register_style( 'langblogR-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
      wp_enqueue_script( 'comment-reply' );
    }

    //adding scripts file in the footer
    wp_register_script('langblogR-easing', get_stylesheet_directory_uri() . '/library/js/libs/jquery.easing.min.js', 'jquery', '', true);
    wp_register_script('langblogR-js', get_stylesheet_directory_uri() . '/library/js/langblog.js', array( 'jquery' ), '', true );

    // enqueue styles and scripts
    wp_enqueue_script( 'langblogR-modernizr' );
    wp_enqueue_style( 'langblogR-stylesheet' );
    wp_enqueue_style('langblogR-ie-only');

    $wp_styles->add_data( 'langblogR-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'langblogR-easing' );
    wp_enqueue_script( 'langblogR-js' );

  }
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function langblogR_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support('post-thumbnails');

	// default thumb size
	set_post_thumbnail_size(300, 300, true);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// wp menus
	//add_theme_support( 'menus' );

	// registering wp3+ menus
	/*register_nav_menus(
		array(
			'footer-links' => __( 'Footer Links', 'langblogRtheme' )
		)
	);*/
} /* end langblogR theme support */


/*********************
MENUS & NAVIGATION
*********************/


// the top menu (should you choose to use one)
/*function langblogR_footer_links() {
    wp_nav_menu(array(
    	'container' => '',                              // remove nav container
    	'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    	'menu' => __( 'Footer Links', 'langblogRtheme' ),   // nav name
    	'menu_class' => 'nav footer-nav clearfix',      // adding custom nav class
    	'theme_location' => 'footer-links',             // where it's located in the theme
    	'before' => '',                                 // before the menu
        'after' => '',                                  // after the menu
        'link_before' => '',                            // before each link
        'link_after' => '',                             // after each link
        'depth' => 0,                                   // limit the depth of the nav
    	'fallback_cb' => 'langblogR_footer_links_fallback' 	// fallback function
	));
}*/ /* end langblogR top link */




// this is the fallback for footer menu
/*function langblogR_footer_links_fallback() {
	// you can put a default here if you like
}*/


/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function langblogR_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  echo '<nav class="pagination">';
  echo paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&laquo;',
    'next_text'    => '&raquo;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );
  echo '</nav>';
} /* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

function langblogR_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function langblogR_excerpt_more($more) {
	global $post;
	return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __( 'Read ', 'langblogR' ) . get_the_title($post->ID).'">'. __( 'Read more &raquo;', 'langblogR' ) .'</a>';
}

?>
