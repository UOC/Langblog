<?php
/*
The comments page for expotfg
*/

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<div class="alert alert-help">
			<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'langblogR'); ?></p>
		</div>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<div id="comments" class="clearfix">

		<h2 class="h3"><?php comments_number(__('No contributions', 'langblogR'), __('1 contribution', 'langblogR'), __('% contributions', 'langblogR'));?></h2>

		<script>var arr="";</script>
		<ol class="commentlist">
			<?php wp_list_comments('type=comment&callback=langblogR_comments'); ?>
		</ol>

		<nav id="comment-nav" class="comment-nav">
			<ul>
				<li><?php previous_comments_link() ?></li>
				<li><?php next_comments_link() ?></li>
			</ul>
		</nav>
	</div>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<div id="respond" class="alert alert-help">
			<p><?php printf( __('You must be %1$slogged in%2$s to post a comment.', 'langblogR'), '<a href="'. wp_login_url( get_permalink() ) .'">', '</a>' ); ?></p>
		</div>
	<?php else : ?>

	<section id="respond" class="respond-form clearfix">

		<h2 id="comment-form-title" class="h3"><?php _e('Post a contribution', 'langblogR') ?></h2>

		<div id="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></div>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

		<?php if ( is_user_logged_in() ) : ?>

		<p class="comments-logged-in-as"><?php _e("Logged in as", "langblogR"); ?>  <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e("Log out of this account", 'langblogR'); ?>" class="link-logout"><?php _e("Log out", 'langblogR'); ?> <?php _e("&raquo;", "langblogR"); ?></a></p>

		<?php else : ?>

		<ul id="comment-form-elements" class="clearfix">

			<li>
				<label for="author"><?php _e("Name", "langblogR"); ?> <?php if ($req) _e("(required)"); ?></label>
				<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e('Your Name*', 'langblogR'); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
			</li>

			<li>
				<label for="email"><?php _e("Mail", "langblogR"); ?> <?php if ($req) _e("(required)"); ?></label>
				<input type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e('Your E-Mail*', 'langblogR'); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
				<small><?php _e("(will not be published)", "langblogR"); ?></small>
			</li>

			<li>
				<label for="url"><?php _e("Website", "langblogR"); ?></label>
				<input type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php _e('Got a website?', 'langblogR'); ?>" tabindex="3" />
			</li>

		</ul>

		<?php endif; ?>

		<p><textarea name="comment" id="comment" placeholder="<?php _e('Your Comment here...', 'langblogR'); ?>" tabindex="4"></textarea></p>

		<?php do_action('comment_form', $post->ID); ?>

		<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Send', 'langblogR') ?>" />
		<?php comment_id_fields(); ?>

		<?php/*<div class="alert alert-info">
			<p id="allowed_tags" class="small"><strong>XHTML:</strong> <?php _e('You can use these tags', 'langblogR'); ?>: <code><?php echo allowed_tags(); ?></code></p>
		</div>*/?>

		</form>
		
	</section>

	<?php endif; // If registration required and not logged in ?>

<?php else: ?>
	<div class="alert alert-help">
		<p class="nocomments"><?php _e("Comments are closed.", 'langblogR'); ?></p>
	</div>
<?php endif; // if you delete this the sky will fall on your head ?>
