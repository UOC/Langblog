<?php
require_once( ABSPATH . WPINC . '/pluggable.php');
require_once( ABSPATH . WPINC . '/registration-functions.php');

$lang = get_locale();
switch ($lang){
	case "es_ES": $ln = "es";break;
	case "nl_NL": $ln = "nl";break;
	case "fr_FR": $ln = "fr";break;
	case "en_EN": $ln = "en";break;
	case "ca_ES": $ln = "ca";break;
	case "ca": $ln = "ca";break;
	case "pl_PL": $ln = "pl";break;
	case "sv_SE": $ln = "su";break;
	default :  $ln = "en";break;
}

?>
<div id="sidebar" class="sidebar m-all t-1of3 d-2of7 clearfix"> 

  <a class="nav-btn show-for-small" id="nav-open-btn" href="#nav">
      <span class="lbl"><?php _e('Menu', 'langblogR'); ?></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
  </a>

  <div id="nav">

    <ul class="nav">

  	<li class="cat-list">
      <h2><?php _e('ACTIVITIES', 'langblogR'); ?></h2>
      <ul>
          <?php wp_list_cats(); ?>
      </ul> 
    </li>

  	<li>
  		<h2><a href="http://langblog.speakapps.org/speakappsinfo/" target="_blank"><?php _e('HELP', 'langblogR'); ?></a></h2></li>
    </li>

    	<?php 
  	global $userdata; 
  	get_currentuserinfo(); 
  	if(current_user_can('edit_posts')){  
  	?>
    	<li>
    		<h2><a href="<?php echo get_option('siteurl'); ?>/wp-admin/" target="_blank"><?php _e('ADMINISTRATION', 'langblogR'); ?></a></h2>
    	</li>
  	<?php 
  	} 
  	?>

    </ul>

    <div id="cercador">
        <form role="search" method="get" class="search-form" action="<?php bloginfo('home'); ?>/">
        	<label class="screen-reader-text" for="s"><?php _e('Search for:', 'langblogR') ?></label>
          <input type="text" value="" name="s" id="s_side" placeholder="<?php _e('search ...', 'langblogR'); ?>" />
          <button type="submit" class="button search-icon" tabindex="-1">
            <span class="visuallyhidden"><?php _e('Search', 'langblogR')?></span>
          </button>
        </form>
    </div>

    <a class="nav-btn" id="nav-close-btn" href="#content"></a>

  </div>

</div>