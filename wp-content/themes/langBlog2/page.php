<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="article" id="post-<?php the_ID(); ?>"> 
	<h1><?php _e('By') ?> <?php the_author() ?> <?php _e('on') ?> <?php the_time('j') ?> <?php the_time('F') ?> <?php the_time('Y') ?></h1>
	  <hr id="sepSingle" />
	 <h2><a href="<?php echo get_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
  <?php the_content('<p>More</p>'); ?>
  <?php // link_pages('<p><strong>'._e('Pages').':</strong> ', '</p>', 'number'); ?>
</div>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
