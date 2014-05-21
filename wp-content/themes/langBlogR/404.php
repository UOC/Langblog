<?php get_header(); ?>

    <div id="main" class="m-all t-2of3 d-5of7 last-col clearfix" role="main">
    	<article id="post-not-found" class="clearfix">

            <section class="article-content">
            	<h1 class="title"><?php _e('Error 404: Page not found!', 'langblogR'); ?></h1>
            </section>

            <section class="article-content">
                <p class="highlight"><?php _e("Sorry, you are looking for something that isn't here!", 'langblogR'); ?></p>
                <p><?php get_search_form(); ?></p>
            </section>


        </article>


		
    </div> <!-- end #main -->


<?php get_footer(); ?>
