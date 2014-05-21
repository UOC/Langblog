<?php
/**
 * All in one configuration video pack category
 * @author Antoni Bertran (abertranb@uoc.edu)
 * @copyright 2010 Universitat Oberta de Catalunya
 * @package all-in-one-video-pack-category.admin
 * @version 27: kaltura_admin_controller.php 2013-11-04 09:15:09Z abertran $
 * @license GPL
 * Date November 2013
 */
/** WordPress Administration Bootstrap */
require_once ('./admin.php');
function getSelect($select, $number, $custom_category = '') 
{
    
    return '<select id="select_value_' . $number . '" name="field-value-' . $number . '">
            <!--option ' . (($select == '') ? 'selected' : '') . ' value="">' . __('No value', 'all-in-one-video-pack-category') . '</option-->
            <option ' . (($select == 'post_name') ? 'selected' : '') . ' value="post_name">' . __('Post Name', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'post_id') ? 'selected' : '') . ' value="post_id">' . __('Post Id', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'blog_name') ? 'selected' : '') . ' value="blog_name">' . __('Blog Name', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'blog_id') ? 'selected' : '') . ' value="blog_id">' . __('Blog Id', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'blog_id_plus_name') ? 'selected' : '') . ' value="blog_id_plus_name">' . __('Blog Id + Blog Name', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'name_theme') ? 'selected' : '') . ' value="name_theme">' . __('Theme Name', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'year') ? 'selected' : '') . ' value="year">' . __('Year', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'month') ? 'selected' : '') . ' value="month">' . __('Month', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'author') ? 'selected' : '') . ' value="author">' . __('Author', 'all-in-one-video-pack-category') . '</option>
            <option ' . (($select == 'custom') ? 'selected' : '') . ' value="custom">' . __('Custom Value', 'all-in-one-video-pack-category') . '</option>
      </select><input type="text" id="custom_text_' . $number . '" name="custom_text_' . $number . '" value="' . $custom_category . '" class="' . ($select != 'custom' ? 'hidden' : '') . '"/>';
}
function getDelete($number) 
{
    
    return '<button type="button" name="delete_select_' . $number . '" id="delete_select_' . $number . '" class="button button-secondary">' . '- ' . __('Delete Category', 'all-in-one-video-pack-category') . '</button>';
}
if (!current_user_can('manage_options')) 
{
	wp_die(__('You do not have sufficient permissions to manage options for this site.', 'all-in-one-video-pack-category'));
}
$kalturaPartnerId = KalturaCategoryHelpers::getOption('kaltura_partner_id');
if (!$kalturaPartnerId) 
{
    echo '<br><div class="error"><h2>' . __('You have to configure All In one video pack', 'all-in-one-video-pack-category') . '</h2></div>'; //Error
    
}
else
{
    $title = __('All in one category configuration', 'all-in-one-video-pack-category');
    get_current_screen()->add_help_tab(array(
        'id' => 'overview',
        'title' => __('Overview', 'all-in-one-video-pack-category') ,
        'content' => '<p>' . __('Select the categories tree to upload a video.', 'all-in-one-video-pack-category') . '</p>' . '<p>' . __('This screen allows you to choose your category tree structure into Kaltura.', 'all-in-one-video-pack-category') . '</p>' . '<p>' . __('You must click the Save Changes button at the bottom of the screen for new settings to take effect.', 'all-in-one-video-pack-category') . '</p>',
    ));
    /**
     * Display JavaScript on the page.
     *
     * @since 3.5.0
     */
    function options_all_in_one_category_add_js() 
    {
        echo '
		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function() {
			var number = jQuery("#total_number").val();
			for(i=0; i<number; i++){
				enableOrDisableCustom(i);
			} 
			
			jQuery("#add_select").click(function() {
				addSelect();
			});
		});
		function enableOrDisableCustom(i) {
			jQuery("#select_value_"+i).change(function() {
				if ("custom" == this.value ){
					jQuery("#custom_text_"+i).show(); 
				} else {
					jQuery("#custom_text_"+i).hide(); 
				}
			});
			jQuery("#delete_select_"+i).click(function() {
				if (confirm("' . __('Are you sure you want delete this row?', 'all-in-one-video-pack-category') . '")) {
			    	jQuery("#row" + i).remove();
			    }
			});

		}
		function addSelect() {
			var number = jQuery("#total_number").val();
		
			var html = \'<select id="select_value_\'+ number +\'" name="field-value-\' + number + \'">\'+
		            \'<!--option value="">' . __('No value', 'all-in-one-video-pack-category') . '</option-->\'+
		            \'<option value="post_name">' . __('Post Name', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="post_id">' . __('Post Id', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="blog_name">' . __('Blog Name', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="blog_id">' . __('Blog Id', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="blog_id_plus_name">' . __('Blog Id + Blog Name', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="name_theme">' . __('Theme Name', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="year">' . __('Year', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="month">' . __('Month', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="author">' . __('Author', 'all-in-one-video-pack-category') . '</option>\'+
		            \'<option value="custom">' . __('Custom Value', 'all-in-one-video-pack-category') . '</option>\'+
		      \'</select><input type="text" id="custom_text_\'+number+\'" name="custom_text_\'+number+\'" value="" class="hidden"/>\';
		    var delete_str = \'<button type="button" name="delete_select_\'+number+\'" id="delete_select_\'+number+\'" class="button button-secondary">\'+
						    \'- ' . __('Delete Category', 'all-in-one-video-pack-category') . '\'+
						\'</button>\';
		    jQuery("#table_categories").append(\'<tr id="row\'+number+\'">\'+
					\'<td><label>' . __('Category', 'all-in-one-video-pack-category') . ' \'+number+\'</label></td>\'+
					\'<td>\'+html+\'</td>\'+
					\'<td>\'+delete_str+\'</td>\'+
				\'</tr>\');
			enableOrDisableCustom(number);
			number ++;
			jQuery("#total_number").val(number);
		
		}
		function removeTableRow(trId){
		}
		//]]>
		</script>';
    }
    //abertranb Doesn't work instead call method
    //add_filter('admin_head', 'options_all_in_one_category_add_js');
    options_all_in_one_category_add_js();
    if (isset($_POST['submit'])) 
    {
        check_admin_referer('update-category-all-in-one');
        $total_number = $_POST['total_number'];
        $total_real = 0;
        
        for ($i = 0; $i < $total_number; $i++) 
        {
            $value = isset($_POST['field-value-' . $i]) ? $_POST['field-value-' . $i] : false;
            if ($value) 
            {
                update_option('all-in-one-category-cat-' . $total_real, $value);
                $custom_text = $value == 'custom' && isset($_POST['custom_text_' . $i]) ? $_POST['custom_text_' . $i] : false;
                if ($custom_text) 
                {
                    update_option('all-in-one-category-cat-custom-' . $total_real, $custom_text);
                }
                else
                {
                    delete_option('all-in-one-category-cat-custom-' . $i);
                }
                $total_real++;
            }
            else
            {
                delete_option('all-in-one-category-cat-' . $i);
                delete_option('all-in-one-category-cat-custom-' . $i);
            }
        }
        update_option('all-in-one-category-total-number', $total_real);
?>
		<div id="message" class="updated"><p><?php
        if (!is_main_site() || !is_multisite()) 
        {
            _e('Category tree updated.', 'all-in-one-video-pack-category');
        }
        else
        {
            _e('Defult multisite category tree updated.', 'all-in-one-video-pack-category');
        }
?>
		</p></div>
	<?php
    }
    $AllInOneCategoryTree = get_option('all-in-one-category-total-number', 1);
?>
		<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php echo esc_html($title); ?></h2>
		<form name="form" action="" method="post">
		<?php wp_nonce_field('update-category-all-in-one'); ?>
		<p><?php _e('You can configure the tree of categories will be created with each post and comment.', 'all-in-one-video-pack-category'); ?></p>
		<h3><?php _e('Common Settings', 'all-in-one-video-pack-category'); ?></h3>
		<table class="widefat" id="table_categories">
			<thead>
				<th><?php _e('Category Name', 'all-in-one-video-pack-category'); ?></th>
				<th><?php _e('Value', 'all-in-one-video-pack-category'); ?></th>
				<th><?php _e('Delete', 'all-in-one-video-pack-category'); ?></th>
			</thead>
			<tfoot>
				<th><?php _e('Category Name', 'all-in-one-video-pack-category'); ?></th>
				<th><?php _e('Value', 'all-in-one-video-pack-category'); ?></th>
				<th><?php _e('Delete', 'all-in-one-video-pack-category'); ?></th>
			</tfoot>
			<tbody>
			<tr>
				<td><label><?php _e('Root Category', 'all-in-one-video-pack-category'); ?></label></td>
				<td><?php echo getSelect(get_option('all-in-one-category-cat-0', 'blog_name') , 0, get_option('all-in-one-category-cat-custom-0')); ?></td>
				<td></td>
			</tr>
	<?php
    for ($i = 1; $i < $AllInOneCategoryTree; $i++) 
    { ?>
			<tr id="row<?php echo $i; ?>">
				<td><label><?php _e('Category', 'all-in-one-video-pack-category'); ?> <?php echo $i; ?></label></td>
				<td><?php echo getSelect(get_option('all-in-one-category-cat-' . $i) , $i, get_option('all-in-one-category-cat-custom-' . $i)); ?></td>
				<td><?php echo getDelete($i); ?></td>
			</tr>
	<?php
    } ?>
			</tbody>
		</table>
		<br />
		<button type="button" name="add_select" id="add_select" class="button button-primary">
		    + <?php _e('Add Category', 'all-in-one-video-pack-category'); ?>
		</button>
		<?php submit_button(); ?>
		<input type="hidden" name="total_number" id="total_number" value="<?php echo $AllInOneCategoryTree; ?>" />
		  </form>
		</div>
<?php
}