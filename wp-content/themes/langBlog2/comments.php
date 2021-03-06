<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
$oddcomment = 'alt';
$path2Theme = 'wp-content/themes/langBlog2/';
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php if ($comments) : ?> 
<h1 id="comments"> 
  
  <?php comments_number('No contributions', '1 contribution', '% contributions' );?></h1>
  
 <?php endif; ?>
  
<ol class="commentlist">
	<ol class="commentlist">
	<?php 
	
	echo '<script>var arr="";</script>';
	wp_list_comments('type=comment&callback=mytheme_comment'); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p id="respond">Has de fer <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">login</a> per comentar</p>
<?php else : 
echo '<script>
		var arrowPressed=false;
		function showArrow(id){
			var fl = flechas.split("*");
			for(var i=0;i<fl.length;i++){
				if(document.getElementById("flecha"+fl[i]))
				document.getElementById("flecha"+fl[i]).src="'.get_option('siteurl').'/'.$path2Theme.'images/noflecha.gif";
			}
			if(id!=0)
				document.getElementById("flecha"+id).src="'.get_option('siteurl').'/'.$path2Theme.'images/alignleft02.gif";
			
			arrowPressed=!arrowPressed;
		}
		
	</script>';
?>

<?php if ('open' == $post->comment_status) : ?>
<h1 id="respond"><?php _e('Post a contribution') ?></h1>
<?php endif; ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <?php if ( $user_ID ) : ?>
  <h3 id="respond"> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.
	
	
	<?php
			if(function_exists('submitdiv_at_bottom')){
	?>
	<div id="stepsComm">
		<p>
			<img onmouseover="document.getElementById('helperCom').style.display='inline';document.getElementById('titleHelperCom').innerHTML='<?php echo __("Write text","langBlog2");?>';document.getElementById('imgHelperCom').src='<?php echo get_option("siteurl");?>/<?php echo $path2Theme;?>images/mov1.gif';" onmouseout="document.getElementById('helperCom').style.display='none';" src="<?php echo get_option('siteurl');?>/<?php echo $path2Theme;?>images/comNum1.png" />&nbsp;&nbsp;<?php echo __("Write text","langBlog2");?>&nbsp;&nbsp;
			
			<img onmouseover="document.getElementById('helperCom').style.display='inline';document.getElementById('titleHelperCom').innerHTML='<?php echo __("Add Audio/Video","langBlog2");?>';document.getElementById('imgHelperCom').src='<?php echo get_option("siteurl");?>/<?php echo $path2Theme;?>images/mov2.gif';" onmouseout="document.getElementById('helperCom').style.display='none';"src="<?php echo get_option('siteurl');?>/<?php echo $path2Theme;?>images/comNum2.png" />&nbsp;&nbsp;<?php echo __("Add Audio/Video","langBlog2");?>&nbsp;&nbsp;
			<img onmouseover="document.getElementById('helperCom').style.display='inline';document.getElementById('titleHelperCom').innerHTML='<?php echo __("Send","langBlog2");?>';document.getElementById('imgHelperCom').src='<?php echo get_option("siteurl");?>/<?php echo $path2Theme;?>images/mov3.gif';" onmouseout="document.getElementById('helperCom').style.display='none';"src="<?php echo get_option('siteurl');?>/<?php echo $path2Theme;?>images/comNum3.png" />&nbsp;&nbsp;<?php echo __( "Send","langBlog2");?>
			</p>
	</div>
		<div id="helperCom">
			<p id="titleHelperCom"></p>
			<img id="imgHelperCom" src="" />
		</div>
<?php } ?>		
		
		
		
   </h3>
  <?php else : ?>
  <p> 
    <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="30" tabindex="1" />
    <label for="author">Name <span class="required">
    <?php if ($req) echo "(required)"; ?>
    </span> </label>
  </p>
  <p> 
    <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="30" tabindex="2" />
    <label for="email">Email <span class="required">
    <?php if ($req) echo "(required)"; ?>
    </span> </label>
  </p>
  <p> 
    <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="30" tabindex="3" />
    <label for="url">Web page</label>
  </p>
  <?php endif; ?>
  <!--<p><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></p>-->
  <div> 
    <textarea name="comment" id="comment" cols="150" rows="15" tabindex="4"></textarea>
  </div>
  <br/>
	<?php do_action('comment_form', $post->ID); ?>
  <div> 
    <input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Send') ?>" />
    <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
  </div>
  <br/>
  
</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
