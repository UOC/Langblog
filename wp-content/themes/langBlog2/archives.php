<?php
/*
Template Name: ARXIUS
*/
?>
<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
<div class="article"> 
  <h1 class="headline"><?php _e('Monthly contributions :'); ?></h1>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>
  <h1 class="headline"><?php _e('Contributions by activity :'); ?></h1>
  <ul>
    <?php wp_list_cats(); ?>
  </ul>
</div></div>
<?php get_footer(); ?>