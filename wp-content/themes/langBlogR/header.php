<!doctype html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php wp_title( '|', true, 'right' ); ?></title>
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="cleartype" content="on">

        <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
        <!--[if IE]>
            <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
        <![endif]-->
       
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>

        <!-- drop Google Analytics Here -->
        <!-- end analytics -->

    </head>

    <body <?php body_class(); ?>>

        <div id="container">

            <header id="header" class="header" role="banner">

                    <div id="inner-header" class="wrap clearfix">
                        
                        <div id="brand" class="brand">
                            <h1 class="site-title">
                                <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" class="logo-langblog"><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/langblog_logo.png" /></a>
                            </h1>
                        </div>

                        <div id="speakapps" class="speakapps">
                            <a href="http://www.speakapps.eu/" title="SpeakApps" class="logo-speakapps" ><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/speakapps_logo.png" /></a>
                        </div>

                    </div> <!-- end #inner-header -->

            </header> <!-- end header -->

            <div id="breadcrumbs" class="wrap clearfix">
                <?php langblogR_breadcrumbs(); ?> 
            </div>

            
            <div id="content" class="wrap clearfix"/>

                <?php get_sidebar(); ?>