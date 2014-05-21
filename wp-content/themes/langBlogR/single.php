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

	<div id="main" class="m-all t-2of3 d-5of7 last-col clearfix" role="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

 			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

                <header class="article-header">

                  <h2 class="byline vcard">
                    <?php printf( __( 'By', 'langblogR') . ' <span class="author">%1$s</span> ' . __('on', 'langblogR') . ' <time class="updated" datetime="%2$s" pubdate>%3$s</time> ' , get_the_author_link(get_the_author_meta( 'ID' )) , get_the_time('Y-m-j'), get_the_time('j F Y'));  ?>
                  </h2>

                  <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

                </header> <!-- end article header -->

                <section class="article-content">
                  <?php the_content('More'); ?>
                </section> <!-- end article section -->

                <footer class="article-footer">

                	<div id="metadata">
					<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Both Comments and Pings are open ?>
												
						<a href="#respond" class="button button-icon"><i class="icon icon-comments"></i><?php _e('You can post a contribution', 'langblogR') ?></a>

					    <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Only Pings are Open ?><?php } ?>

					    <?php /*<span class="link-subscription"><?php post_comments_feed_link( $link_text = __("See subscription", 'langblogR') );?></span>*/ ?>

					</div>

                </footer>

                <?php comments_template(); ?>

            </article> <!-- end article -->

            <?php endwhile; ?>

                  <?php if (function_exists('langblogR_page_navi')) { ?>
                      <?php langblogR_page_navi(); ?>
                  <?php } else { ?>
                      <nav class="wp-prev-next">
                          <ul class="clearfix">
                            <li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "langblogR")) ?></li>
                            <li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "langblogR")) ?></li>
                          </ul>
                      </nav>
                  <?php } ?>

                  
            <?php else : ?>

                <article id="post-not-found" class="hentry clearfix">

                    <header class="article-header">
                        <h1><?php _e("No results found!", "langblogR"); ?></h1>
                    </header>

                    <section class="article-content">
						<p class="highlight"><?php _e("Sorry, no contribution with this criteria!", 'langblogR'); ?></p>
                    </section>

                </article>

            <?php endif; ?>

    </div> <!-- end #main -->

<?php get_footer(); ?>