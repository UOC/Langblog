<form role="search" method="get" id="searchform" action="<?php bloginfo('home'); ?>/" class="search-form clearfix">
  	<label class="screen-reader-text" for="s"><?php _e('Search for:', 'langblogR') ?></label>
    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('search ...', 'langblogR'); ?>" />
    <input type="submit" id="searchsubmit" value="<?php _e('Search', 'langblogR')?>" class="button" tabindex="-1" />
</form>