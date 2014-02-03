<?php get_header(); ?>
<?php if (have_posts()) : ?>
<h1 class="headline"><?php _e('Search results'); ?> </h1>
 
<?php while (have_posts()) : the_post(); ?>
<div class="article" id="post-<?php the_ID(); ?>"> 
  	<h1><?php _e('By') ?> <?php the_author() ?> <?php _e('on') ?> <?php the_time('j') ?> <?php the_time('F') ?> <?php the_time('Y') ?></h1>
	  <hr id="sepSingle" />
	  <<h2><a href="<?php echo get_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
  <?php the_content('More'); ?>
  <div class="feedback"> 
    <?php wp_link_pages(); ?>
	 <span class="link-categorie"><?php _e('posted at'); ?> <?php the_category(', ') ?></span>
    <?php comments_popup_link(__('Contributions'), __('1 contribution'), __('% contributions'), 'link-comentariu', __('No contributions')); ?>
  </div>
</div>
<div class="line"></div>
<?php endwhile; ?>
<?php else : ?>
<h1><?php _e('No contribution found! Try another search.'); ?></h1>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
<?php endif; ?></div>
<?php get_footer(); ?>