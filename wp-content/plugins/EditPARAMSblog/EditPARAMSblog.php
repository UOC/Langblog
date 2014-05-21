<?php
/*
 *	Plugin Name: EditPARAMSblog
 *	Version: 1.0
 *	Plugin URI: ????
 *	Description: Allow to administrator and editors to change values of blog's title and description. 
 *	reAuthor: Chris Moya (cmoyas@uoc.edu) - Universitat Oberta de Catalunya
 *	reAuthor URI: http://www.uoc.edu
*/

$plugin_ver = '1.0';
$plugin_name = 'EditPARAMSblog';
$plugin_shortname = 'EditPARAMSblog';
$plugin_url = 'http://www.uoc.edu';
$author_url = 'http://www.uoc.edu';

$shorttag = 'EditPARAMSblog';
$settings_name = $shorttag.'_settings';
$notes_name = $shorttag.'_savednote';
$mainfile_path = 'options-general.php?page=EditPARAMSblog/EditPARAMSblog.php';
$plugin_path = WP_CONTENT_URL.'/plugins/EditPARAMSblog/';

//Función para determinar los permisos del usuario.
function get_permission($settings,$action) {
$allowed_levelid = array();
if ($action != 'view') { $action = 'edit'; }
$allowed_levelid[] = '7'; //editor role
$allowed_levelid[] = '10'; //admin role
return $allowed_levelid;
}

function admin_menuF() {
	global $plugin_shortname;
	global $path2WP;
	$path2WP = getcwd();
	//Gestión del idioma.
	if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('EditPARAMSblog',PLUGINDIR.'/EditPARAMSblog');
	}
	//add_menu_page($plugin_shortname, $plugin_shortname, 7, '', 'admin_options');
	add_menu_page('EditPARAMSblog', 'EditPARAMSblog', 'edit_others_posts', 'EditPARAMSblog/EditPARAMSblog.php','admin_options');
	}

function admin_options() {
	global $shorttag,$plugin_name,$author_url,$plugin_url,$plugin_ver,$settings_name,$notes_name,$plugin_path,$mainfile_path,$table_prefix,$settings,$user_ID, $user_login;
	$dir = "../wp-content/languages";
	$dirTop = "../";
	if(get_locale()!='') $loc = get_locale();
	else $loc = "en_EN";
	// check for note submission.
	if (isset($_POST[$shorttag.'_notesubmit'])) {
		//check for permission to edit based on user level
		$edit_allowed_levelid = get_permission($settings,'edit');
		global $current_user;
		get_currentuserinfo();
		update_option('blogname', $_POST[$shorttag.'_T']);
		update_option('blogdescription', $_POST[$shorttag.'_D']);
				
		if($_POST['show_on_front']=="posts"){ 
			update_option('show_on_front', 'post');
			update_option('page_on_front', 0);
		}
		if($_POST['show_on_front']=="page"){ 
			update_option('show_on_front', 'page');
			update_option('page_on_front', $_POST['page_on_front']);
		}
		
		if($_POST['langSelect']!=__('Choose Language','EditPARAMSblog') && $_POST['langSelect']!=$loc){
			$loc = $_POST['langSelect'];
			update_option( 'WPLANG', $loc );
		}
			echo '<div class="updated" style="padding:5px;"><b>'.__('Your changes has been saved.','EditPARAMSblog').'</b><br />'.__('Refresh page to see changes.','EditPARAMSblog').'</div>';
	}
	// start output
	
	
	$textarea_content = get_bloginfo();
	if ($textarea_content) { $ttl = htmlspecialchars(stripslashes($textarea_content)); }
	$textarea_content = get_bloginfo('description');
	if ($textarea_content) { $desc = htmlspecialchars(stripslashes($textarea_content)); }

	//Funciones para des-habilitar los campos del formulario.
	echo'
		<script>
			function editFields(){
				document.getElementById("'.$shorttag.'_T").disabled=false;
				//if( is_array($lang_files) && !empty($lang_files) ) {
					document.getElementById("langSelect").disabled=false;
				//}
				document.getElementById("'.$shorttag.'_D").disabled=false;
				document.getElementById("'.$shorttag.'_notesubmit").disabled=false;
				document.getElementById("'.$shorttag.'editBtn").value="'.__('Cancel','EditPARAMSblog').'";
				document.getElementById("'.$shorttag.'editBtn").onclick=NoeditFields;
				document.getElementById("show_on_frontPost").disabled=false;
				document.getElementById("show_on_frontPages").disabled=false;
				document.getElementById("page_on_front").disabled=false;
			}
			function NoeditFields(){
				document.getElementById("'.$shorttag.'_T").value="'.$ttl.'";
				document.getElementById("'.$shorttag.'_D").value="'.$desc.'";
				document.getElementById("'.$shorttag.'_T").disabled=true;
				document.getElementById("'.$shorttag.'_D").disabled=true;
				document.getElementById("'.$shorttag.'_notesubmit").disabled=true;
				document.getElementById("'.$shorttag.'editBtn").value="'.__('Edit','EditPARAMSblog').'";
				document.getElementById("'.$shorttag.'editBtn").onclick=editFields;';
				if( is_array($lang_files) && !empty($lang_files) ) {
					echo 'document.getElementById("langSelect").disabled=true;';
				}
				echo '
				document.getElementById("show_on_frontPost").disabled=true;
				document.getElementById("show_on_frontPages").disabled=true;
				document.getElementById("page_on_front").disabled=true;
			}
		</script>
	';
	
	//Buscamos todos los archivos instalados de idioma en el Blog.
	$i=0;
	$files = array();
	if (is_dir($dir)){
    	if ($gd = opendir($dir)){
        	while (($archivo = readdir($gd)) !== false){
        		if ($archivo != "." & $archivo != ".."){
            		if(!in_array($archivo,$files)){
            			$f = explode(".",$archivo);
            			if($f[1]!="mo"){
            				$i;
            				$files[$i]=$f[0];
            				$i++;
            			}
            		}
				}
			}
			closedir($gd);
		}
	}
	echo '<div class="wrap">';
		echo '<h2>'.wp_specialchars($plugin_name).'</h2>';
		echo '<form method="post" action="">
		<h5>'.__("Blog's name.",'EditPARAMSblog').'</h5>';
		echo '<input disabled name="'.$shorttag.'_T" id="'.$shorttag.'_T" style="width:80%;margin-bottom:10px;" value="'.$ttl.'">
		</input><br />';
		echo '<h5>'.__("Blog's description.",'EditPARAMSblog').'</h5>
		<textarea disabled name="'.$shorttag.'_D" id="'.$shorttag.'_D" rows="3" style="width:80%;margin-bottom:10px;">';
		echo $desc;
		echo '</textarea><br />';
		echo '<h5>'.__("Front page displays",'EditPARAMSblog').'</h5>
			<h5>&nbsp;&nbsp;&nbsp;
			<input name="show_on_front" id="show_on_frontPages" type="radio" value="posts" class="tog"';
		if(get_option('show_on_front')=="post") echo 'checked="checked"';
		echo 'disabled/>
&nbsp;&nbsp;&nbsp;'.__("Your latest posts",'EditPARAMSblog').'<br/>&nbsp;&nbsp;&nbsp;
			<input name="show_on_front" id="show_on_frontPost" type="radio" value="page" class="tog"';
		if(get_option('show_on_front')=="page") echo 'checked="checked"';
		echo 'disabled/>
&nbsp;&nbsp;&nbsp;<a href="edit.php?post_type=page">'.__("Static page",'EditPARAMSblog').'</a><br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
'.__("Front page:",'EditPARAMSblog').': <select name="page_on_front" id="page_on_front" disabled>';

$pages = get_pages(); 
foreach ( $pages as $page ) {
  	$option = '<option value="'.$page->ID.'"';
	if(get_option('show_on_front')=="page" && get_option('page_on_front')==$page->ID) echo $option .=' selected';
	$option .= '>';
	$option .= $page->post_title;
	$option .= '</option>';
	echo $option;
}
echo '	</select>
			</h5>';

			
		if( is_dir( ABSPATH . LANGDIR ) && $dh = opendir( ABSPATH . LANGDIR ) )
			while( ( $lang_file = readdir( $dh ) ) !== false )
				if( substr( $lang_file, -3 ) == '.mo' )
					$lang_files[] = $lang_file;
		$lang = get_option('WPLANG');

		if( is_array($lang_files) && !empty($lang_files) ) {
			?>

			
			<tr valign="top"> 
				<th width="33%" scope="row"><?php _e('Blog language:') ?></th> 
				<td>
					<select name="langSelect" id="langSelect" disabled>
						<?php mu_dropdown_languages( $lang_files, get_option('WPLANG') ); ?>
					</select>
				</td>
			</tr> 
			<?php
		}
			
	echo '	<br /><br />
		<input disabled type="submit" class="button-primary" style="'.$buttonstyle.'" name="'.$shorttag.'_notesubmit" id="'.$shorttag.'_notesubmit" value="'.__(' Save ','EditPARAMSblog').'" />
		<input id="'.$shorttag.'editBtn" name="'.$shorttag.'editBtn" type="Button" class="button-primary" style="'.$buttonstyle.'" value="'.__(' Edit ','EditPARAMSblog').'" onclick="editFields();return false;"/>

		</form>
	</div>';
}

//Colocamos el enlace del plugin en el menú principal.
add_action('admin_menu', 'admin_menuF');
?>