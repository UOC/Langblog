<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php $attachment_link = get_the_attachment_link($post->ID, true, array(450, 800)); // This also populates the iconsize for the next line ?>
<?php $_post = &get_post($post->ID); $classname = ($_post->iconsize[0] <= 128 ? 'small' : '') . 'attachment'; // This lets us style narrow icons specially ?>
<div class="article" id="post-<?php the_ID(); ?>"> 
<h1><?php _e('By') ?> <?php the_author() ?> <?php _e('on') ?> <?php the_time('j') ?> <?php the_time('F') ?> <?php the_time('Y') ?></h1>
	  <hr id="sepSingle" />
	  <<h2><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> 
	    &raquo; <a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent link: <?php the_title(); ?>"> 
	    <?php the_title(); ?></a></h2>


  <div class="entrytext"> 
    <p class="<?php echo $classname; ?>"><?php echo $attachment_link; ?><br />
      <?php echo basename($post->guid); ?></p>
    <?php the_content('More'); ?>
    <?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
    <div id="metadata"> 
      <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?><?php _e('You can post a'); ?> <a href="#respond"><?php _e('CONTRIBUTION'); ?></a> | 
      <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>  <?php } comments_rss_link(_e('See RSS 2.0')); ?><BR />
      <?php  edit_post_link(_e('Edit this contribution'),'',''); ?>
    </div>
  </div>
  <?php comments_template(); ?>
  <?php endwhile; else: ?>
  <p><span class="highlight"><?php _e('Sorry, no attachment with this criteria!'); ?></span></p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
