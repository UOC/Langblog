<?php 

add_action('wp_head', 'myplugin_js_header' );

function myplugin_js_header(){
  wp_print_scripts( array( 'sack' ));
?>
<script type="text/javascript">
function myplugin_cast_Read(comm,commId){
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
add_action('wp_ajax_my_special_action', 'my_action_callback');
add_action('wp_ajax_nopriv_my_special_action', 'my_action_callback');

function my_action_callback() {
	global $user_info;
	$path2Theme = 'wp-content/themes/langBlog2/';
	$current_user = wp_get_current_user();
	$comment_IDRead = $_POST['comm'];
	$comment_post_IDRead = $_POST['commId'];
	$commValue= get_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,true);
	if($commValue==0 || $commValue==""){
		update_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,1);
		echo " document.getElementById('imgC".$comment_IDRead."').src=\"".get_bloginfo("url")."/".$path2Theme."images/leido.gif\";";
	}else{
		update_comment_meta($comment_IDRead,'_AlreadyReadComm'.$current_user->ID,0);
		echo " document.getElementById('imgC".$comment_IDRead."').src=\"".get_bloginfo("url")."/".$path2Theme."images/noleido.gif\";";
	}
}


function mytheme_comment($comment, $args, $depth) {
	$path2Theme = 'wp-content/themes/langBlog2/';
	global $user_info;
	global $settings;
	$user_info = wp_get_current_user();
	global $wpdb;
	$GLOBALS['comment'] = $comment;
	if ($GLOBALS['comment']->user_id) $user=get_userdata($GLOBALS['comment']->user_id);
	echo '<div class="commentmetadata"><div class="comment-meta">';
	$roleUserAuthor=0;
	$userb = new WP_User( $comAuth );
	if($userb->roles[0]!='administrador' && $userb->roles[0]!='administrator' && $userb->roles[0]!='editor') $roleUserAuthor=1;
	$r=0;
	if(get_comment_meta($GLOBALS["comment"]->comment_ID,'_AlreadyReadComm'.$user_info->ID,true)==0){
		$r=0;
		echo '<a href="#" onclick="myplugin_cast_Read('.$GLOBALS["comment"]->comment_ID.','.$GLOBALS["comment"]->comment_post_ID.');return false;"><img id="imgC'.$GLOBALS["comment"]->comment_ID.'" src="'. get_bloginfo("url").'/'.$path2Theme.'images/noleido.gif" border="0" /></a>&nbsp;';
	}else{
		$r=1;
		echo '<a href="#" onclick="myplugin_cast_Read('.$GLOBALS["comment"]->comment_ID.','.$GLOBALS["comment"]->comment_post_ID.');return false;"><img id="imgC'.$GLOBALS["comment"]->comment_ID.'" src="'. get_bloginfo("url").'/'.$path2Theme.'images/leido.gif" border="0" /></a>&nbsp;';
	}	
	
	if($roleUserAuthor==1){echo '<span class="comment-metaReadAuthor">';}
	else{echo '<span class="comment-metaReadEditor">';}
	
	if($r==1){ echo '<span class="comment-metaRead">';}

	echo ''.comment_date('n/j/Y -  ').comment_time().comment_date(' ').'<strong>'.comment_author().'</strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
	$nC=(int) $GLOBALS['comment']->comment_ID;
	echo '	<script>arr+="+'.$nC.'";</script></span>';
	echo comment_text();
	echo '<div class="hrEndTablaComm"></div><br></div></div>';
}