<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="article" id="post-<?php the_ID(); ?>"> 
  <h1>
    <?php the_title(); ?>
  </h1>
  <?php the_content('<p>More</p>'); ?>
  <?php // link_pages('<p><strong>'._e('Pages').':</strong> ', '</p>', 'number'); ?>
</div>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
