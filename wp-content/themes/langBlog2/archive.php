<?php get_header(); ?>
<?php if (have_posts()) : ?>
<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

<?php while (have_posts()) : the_post(); ?>
<div class="article" id="post-<?php the_ID(); ?>"> 
  	<h1><?php _e('By') ?> <?php the_author() ?> <?php _e('on') ?> <?php the_time('j') ?> <?php the_time('F') ?> <?php the_time('Y') ?></h1>
	  <hr id="sepSingle" />
	 <h2><a href="<?php echo get_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
  <?php the_content('More'); ?>
  <div id="metadata">
       		
			<?php
			$commentscount = get_comments_number();
			?>
			
			<span id="contributions"><a href="./?p=<?php echo the_ID();?>#respond"><img src="<?php bloginfo('stylesheet_directory');?>/images/v2/iconoComentario.png" width="19" height="16"/>&nbsp;<?php _e('You can post a') ?>&nbsp;<?php _e('contribution') ?><?php echo " (".$commentscount.")";?></a></span>
		<?php	?>
			 
		
			<!--<img src="<?php bloginfo('stylesheet_directory');?>/images/v2/iconoOjo.png" width="16" height="10"/>-->
			&nbsp;&nbsp;
			<!--<?php comments_rss_link(__("See subscription")); ?>-->
			<!--<img src="<?php bloginfo('stylesheet_directory');?>/images/v2/iconoEditar.png" width="16" height="15"/>-->
			&nbsp;&nbsp;
			<span id="contributions"><a href="./wp-admin/post.php?post=<?php echo the_ID();?>&action=edit"><img src="<?php bloginfo('stylesheet_directory');?>/images/v2/iconoEditar.png" width="16" height="15"/><?php _e('Edit this contribution'); ?></a></span>
			
			
			
			
</div>
</div>
<div style="height:40px;"></div>
<?php endwhile; ?>
<?php else : ?> 
<h2><?php _e('Not found!'); ?></h2>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
<?php endif; ?></div>
<?php get_footer(); ?>