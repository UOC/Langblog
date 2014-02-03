<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title> 
<?php bloginfo('name'); ?>
<?php if ( is_single() ) { ?>
&raquo; Blog Archive 
<?php } ?>
<?php wp_title(); ?>
</title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/engine.js"></script>
<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/qtip.js" ></script>
<?php wp_head(); ?>

<script>
var ali_ie = navigator.appName.indexOf("Microsoft") != -1;
var flechas="";
</script>

<style type="text/css" media="screen">

<?php
// Checks to see whether it needs a sidebar or not
if ( !$withcomments && !is_single() ) {
?>
	#wrapper { border: none; background-position:center; margin: 0px auto auto auto;}
<?php } else { // No sidebar ?>
	#wrapper { border: none; background-position:center; margin: auto auto auto auto;  }
<?php } ?>

</style>

</head>
<body>

<div id="wrapper"/>
	<div id="header">
		<div id="logoLB"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/langblog_logo.png" /></a></div>
		<div id="logoUOC"></div>
        <div id="topRounded">
        	<!-- Cmoyas quitamos el boton back por el breadcrumb -->
            <div id="navigation"> 
                <div class="alignleft"><?php //previous_post_link(__('Back')) ?>
                <a href="<?php bloginfo('url'); ?>" ><?php _e('Home') ?></a> &gt; <?php the_category(', ') ?> &gt; <?php the_title(); ?>
                </div>
                <div class="alignright"></div>
            </div>
            <!-- Cmoyas quitamos el boton back por el breadcrumb -->
        </div>
    </div>
    <div id="middle" class="clearfix"/>
		<?php get_sidebar(); ?>
		<div id="content">