<?php get_header(); ?>

    <div id="main" class="m-all t-2of3 d-5of7 last-col clearfix" role="main">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php $attachment_link = get_the_attachment_link($post->ID, true, array(450, 800)); // This also populates the iconsize for the next line ?>
    <?php $_post = &get_post($post->ID); $classname = ($_post->iconsize[0] <= 128 ? 'small' : '') . 'attachment'; // This lets us style narrow icons specially ?>

              <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

                <header class="article-header">

                  <h2 class="byline vcard">
                    <?php printf( __( 'By', 'langblogR') . ' <span class="author">%1$s</span> ' . __('on', 'langblogR') . ' <time class="updated" datetime="%2$s" pubdate>%3$s</time> ' , get_the_author_link(get_the_author_meta( 'ID' )) , get_the_time('Y-m-j'), get_the_time('j F Y'));  ?>
                  </h2>

                  <h1 class="title"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> <span class="sep">&raquo;</span> <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

                </header> <!-- end article header -->

                <section class="article-content">
                  <p class="<?php echo $classname; ?>"><?php echo $attachment_link; ?><br /><?php echo basename($post->guid); ?></p>
                  <?php the_content('More'); ?>
                </section> <!-- end article section -->

                <footer class="article-footer">

                  <div id="metadata">
                  <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Both Comments and Pings are open ?>
                                
                    <a href="#respond" class="button button-icon"><i class="icon icon-comments"></i><?php _e('You can post a contribution', 'langblogR') ?></a>

                  <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Only Pings are Open ?><?php } ?>
                            
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
                        <p class="highlight"><?php _e("Sorry, you are looking for something that isn't here!", 'langblogR'); ?></p>
                        <p><?php get_search_form(); ?></p>
                    </section>

                  </article>

              <?php endif; ?>

    </div> <!-- end #main -->

<?php get_footer(); ?>
