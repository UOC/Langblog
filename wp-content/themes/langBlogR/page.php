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

              </article> <!-- end article -->

              <?php endwhile; ?>
                  
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
