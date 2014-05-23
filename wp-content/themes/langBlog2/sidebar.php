<?php
require_once( ABSPATH . WPINC . '/pluggable.php');
require_once( ABSPATH . WPINC . '/registration-functions.php');
?>
 <div id="sidebar"> 
  <ul>
	<li id="menu1"><?php _e('ACTIVITIES'); ?>
      	<ul id="subcat1" style=" margin:0px; padding:0px;">
        	<?php wp_list_cats(); ?>
      	</ul> 
    </li>		
	
	<?php
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
	
	<li id="menu2" onclick="window.open('http://langblog.speakapps.org/speakappsinfo/');"><?php _e('HELP'); ?></li>
		
      <ul id="subcat7" style="display:none; margin:0px; padding:0px;">
			
      </ul>
    </li>
  		<?php 
			if(get_locale()!='') $loc = get_locale();
			else $loc = "en_EN";
			global $userdata; get_currentuserinfo(); if(current_user_can('edit_posts')){  
		?>
  		<li id="menu2" onclick="document.location.href='<?php echo get_option('siteurl'); ?>/wp-admin/';"><?php _e('ADMINISTRATION'); ?></li>
													<?php } //end if?>
  </ul>
  <div id="cercador">
      <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
      	<a href="#">  <input class="search" type="submit" id="searchsubmit" value="<?php _e('Search')?>" /></a>
          <input type="text" value="<?php _e('search ...') ?>" name="s" id="s" onfocus="this.value=''" />
      </form>
  </div>
</div>