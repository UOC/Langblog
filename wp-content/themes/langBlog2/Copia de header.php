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
</head>
<body>
<div id="wrapper">
  <div id="header">
    <form id="searchform" method="get" action="<?php bloginfo('url'); ?>/index.php">
      <input name="s" type="text" id="Search" value="search..." size="25" maxlength="40"/>
    </form>
   <!-- <ul id="menu-navigation">
      <li><a title="<?php bloginfo('name'); ?>" href="<?php bloginfo('url'); ?>">home</a></li>
      <li><a title="categories" href="#">cursos</a></li>
      <li><a title="archives" href="#">posts</a></li>
      <li><a title="links" href="#">links</a></li>
      <li><a title="contact" href="#">contacte</a></li>
    </ul>-->
  </div>
  <div id="middle" class="clearfix">
    <div id="splash"><a title="<?php bloginfo('name'); ?>" href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/splash.gif" width="568" height="118" border="0" alt="<?php bloginfo('description'); ?>"/></a></div>
	<?php get_sidebar(); ?>
	<div id="content">