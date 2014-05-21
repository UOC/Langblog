<?php


/************* INCLUDE NEEDED FILES ***************/

/*
library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
*/
require_once('library/bones.php'); // if you remove this, bones will break


/*
library/admin.php
	- removing some default WordPress dashboard widgets (disabled)
	- adding custom login css
	- changing text in footer of admin
	- theme options
*/
require_once('library/admin.php');



/*
Translations
	- adding support for other languages
*/
load_theme_textdomain( 'langblogR', get_template_directory() .'/languages' );
$locale = get_locale();
$locale_file = get_template_directory() ."/languages/$locale.php";
if ( is_readable($locale_file) ) require_once($locale_file);


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/************* THUMBNAIL SIZE OPTIONS *************/

add_image_size( 'langblogR-thumb-600', 600, 150, true );
add_image_size( 'langblogR-thumb-300', 300, 100, true );


/************* BREADCRUMBS *************/

function langblogR_breadcrumbs() {
    if ( !is_front_page() ) {
        echo '<div class="breadcrumbs">';
        echo '<a href="' . get_option('home') . '">' . __('Home', 'langblogR') . "</a> <span class='sep'>&rsaquo;</span> ";
        
        if ( is_attachment() ) {  
           $ancestors = get_post_ancestors($post);
            foreach($ancestors as $id) {
                echo "<a href='" . get_permalink( $id ) . "' title='" . get_the_title( $id ) . "'>" . get_the_title( $id ) . "</a> <span class='sep'>›</span> ";
            }
           echo the_title();
        } elseif ( is_category() || is_single() ) {
                the_category('<span class="sep">&middot;</span>','&title_li=');
            if ( is_single() ) {
                echo " <span class='sep'>›</span> ";
                the_title();
            }
        }elseif ( is_page() ) {
            echo the_title();
        } elseif ( is_day() ) {
            echo get_the_date();
        } elseif ( is_month() ) {
            echo get_the_date('F Y');
        } elseif ( is_year() ) {
            echo get_the_date('Y');
        } else {
            _e( 'Blog Archives', 'langblogR' );
        }
        echo '</div>';
    }/*else{
    	echo '<div class="breadcrumbs">' . __('Home', 'langblogR') . "</div>";
    }*/
} 


/************* Comments Layout *****************/

function langblogR_comments($comment, $args, $depth) {
	global $user_info;
    $GLOBALS['comment'] = $comment; 
    $user_info = wp_get_current_user();
    $read_cls = "";
	if( !(get_comment_meta($GLOBALS["comment"]->comment_ID,'_AlreadyReadComm'.$user_info->ID,true)==0))
		$read_cls="read";	
	?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix <?php if (get_option('show_avatars')): ?>with-avatar<?php endif; ?>">
			<header class="comment-author vcard clearfix">
				<?php if (get_option('show_avatars')): ?>
				<?php echo get_avatar( $comment, 48 ); ?>
				<?php endif; ?>
				<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
				<?php
					echo '<a href="#" class="link-flag ' . $read_cls . '" onclick="langblogR_cast_Read('.$GLOBALS["comment"]->comment_ID.','.$GLOBALS["comment"]->comment_post_ID.');return false;" id="flag_'.$GLOBALS["comment"]->comment_ID.'"><i class="icon icon-flag"></i></a>';
				?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__('n/j/Y - g:i a')); ?></a></time>
			</header>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
				<?php if ($comment->comment_approved == '0') : ?>
					<div class="alert alert-help">
						<p><?php _e('Your comment is awaiting moderation.', 'langblogR') ?></p>
					</div>
				<?php endif; ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			<?php edit_comment_link(__('(Edit)', 'langblogR'),' ','') ?>
		</article>
	<!-- </li> is added by WordPress automatically -->
<?php
}


function langblogR_createMetakey($comment_id){
	if(!get_comment_meta($comment_id, 'type', true)){
		$id = langblogR_getCommentKalturaID($comment_id);
		if($id!=""){
			if(langblogR_getKalturaCommentType($id) == 1) update_comment_meta($comment_id, 'type', 1);
			else if (langblogR_getKalturaCommentType($id) == 2) update_comment_meta($comment_id, 'type', 2);
				 else if(langblogR_getKalturaCommentType($id) == 5) update_comment_meta($comment_id, 'type', 5);
		}else update_comment_meta($comment_id, 'type', 0);
	}
}

function langblogR_getKalturaCommentType($idEntry){
  $file = "all-in-one-video-pack_/API/getType.php";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, plugin_dir_url( $file )."getType.php?id=".$idEntry);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function langblogR_getCommentKalturaID($comment_id){
	$Comm = get_comment($comment_id);
	$text = $Comm->comment_content;
	$text = explode('entryid="',$text);
	$text = explode('"',$text[1]);
	return $text[0];
}

function langblogR_createMetakeyIfNotExists($comments){
	foreach($comments as $comment){
		if(get_comment_meta($comment->comment_ID, 'type', true)==""){
			$id = langblogR_getCommentKalturaID($comment->comment_ID);
			if($id!=""){
				if(langblogR_getKalturaCommentType($id) == 1) update_comment_meta($comment->comment_ID, 'type', 1);
				else if (langblogR_getKalturaCommentType($id) == 2) update_comment_meta($comment->comment_ID, 'type', 2);
					 else if(langblogR_getKalturaCommentType($id) == 5) update_comment_meta($comment->comment_ID, 'type', 5);
			}else update_comment_meta($comment->comment_ID, 'type', 0);			
		}
	}
	return $comments;
}
// ! Set Comment metakey from Kaltura


add_action('wp_head', 'myplugin_js_header' );

function myplugin_js_header(){
  wp_print_scripts( array( 'sack' ));
?>
<script type="text/javascript">
function langblogR_cast_Read(comm,commId){
   var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );
	mysack.execute = 1;
	mysack.method = 'POST';
	mysack.setVar("action", "my_special_action");
	mysack.setVar("comm",comm);
	mysack.setVar("commId",commId);
	mysack.onError=function(){alert('Ajax error' )};
	mysack.runAJAX();
	return true;
}
</script>
<?php
}


function langblogR_my_action_callback() {
	global $user_info;
	$current_user = wp_get_current_user();
	$comment_IDRead = $_POST['comm'];
	$comment_post_IDRead = $_POST['commId'];
	$commValue= get_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,true);
	echo 'var j = jQuery.noConflict();';
	if($commValue==0 || $commValue==""){
		update_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,1);
		echo "j('#flag_".$comment_IDRead."').addClass('read');";
	}else{
		update_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,0);
		echo "j('#flag_".$comment_IDRead."').removeClass('read');";
	}

	error_log("error");
}


//Set Comment metakey from Kaltura
	//video ->1
	//img   ->2
	//sound ->5
add_action('comment_post', 'langblogR_createMetakey',1 );
add_filter('comments_array', 'langblogR_createMetakeyIfNotExists',2);

add_action('wp_ajax_my_special_action', 'langblogR_my_action_callback');
add_action('wp_ajax_nopriv_my_special_action', 'langblogR_my_action_callback');

