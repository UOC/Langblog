<?php 
	  function comments_rss_link2(){
	  	global $id;
		$post_id = (int) $id;
	  	//post_comments_feed_link(__("See subscriptionV"),"$post_id&id=v");
	  }


if($comment_IDRead=$_GET["c"] && $comment_post_IDRead=$_GET["i"]){
	$comment_IDRead=$_GET["c"];
	$comment_post_IDRead=$_GET["i"];
	$n = get_post_meta($comment_post_IDRead,'_unreadComm'.$current_user->ID,true);
	
	$commentscount = get_comments_number();
	if($n>$commentscount) $n=$commentscount;
	else $n++;
	update_post_meta($comment_post_IDRead,'_unreadComm'.$current_user->ID,$n);
	
	if(get_comment_meta($post->ID,'_AlreadyReadComm'.$current_user->ID,true)==""){
		//Leido
		update_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,1);
	}
}
if($comment_IDunRead=$_GET["cu"] && $comment_post_IDunRead=$_GET["iu"]){
	$comment_IDunRead=$_GET["cu"];

	$comment_post_IDunRead=$_GET["iu"];
	$nu = get_post_meta($comment_post_IDunRead,'_unreadComm'.$current_user->ID,true);
	$commentsuncount = get_comments_number();
	if($n>$commentsuncount) $nu=$commentsuncount;
	else $nu--;
	
	update_post_meta($comment_post_IDunRead,'_unreadComm'.$current_user->ID,$nu);
	
	
	if(get_comment_meta($post->ID,'_AlreadyReadComm'.$current_user->ID,true)!=1){
		//noLeido
		update_comment_meta($comment_IDunRead,'_AlreadyReadComm'.$current_user->ID,0);
	}
}
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


<div class="article" id="post-<?php the_ID(); ?>"> 
  <h1><?php _e('By') ?> <?php the_author() ?> <?php _e('on') ?> <?php the_time('j') ?> <?php the_time('F') ?> <?php the_time('Y') ?></h1>
  <hr id="sepSingle" />
  <h2><a href="<?php echo get_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
  
  <span class="txtPost"><?php the_content('More'); ?></span>
  <?php link_pages('<p><strong>'.__('Back').': </strong> ', '</p>', 'number'); ?>
  <div id="metadata">
	
	
	
    <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							
   <span id="contributions"><a href="#respond"><img src="<?php bloginfo('stylesheet_directory');?>/images/v2/iconoComentario.png" width="19" height="16"/>&nbsp;<?php _e('You can post a') ?>&nbsp;<?php _e('contribution') 
   /*Modified abertranb 20121009*/ 
    /* _e('You can post a') ?>&nbsp;<?php _e('contribution') */ ?></a></span>

    <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?><?php }?>
					
					<!--&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php bloginfo('stylesheet_directory');?>/images/v2/iconoOjo.png" width="16" height="10"/>&nbsp;&nbsp;<?php comments_rss_link(__("See subscription")); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php comments_rss_link2(__("See subscriptionV")); ?>-->
</div><br/><br/>
</div>
<?php comments_template(); ?>
<?php endwhile; else: ?>
<p><span class="highlight"><?php _e('Sorry, no contribution with this criteria!') ?></span></p>
<?php endif; ?></div>
<?php get_footer(); ?>