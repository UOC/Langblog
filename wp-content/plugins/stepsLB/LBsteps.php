<?php
/*
Plugin Name: LBSteps
Plugin URI: http://uoc.edu
Description: STEPS for LB
Version: None
Author: Chris Moya
Author URI: http://uoc.edu
License: Public Domain
*/

add_action( 'add_meta_boxes', 'cd_meta_box_add' );
add_action( 'admin_menu' , 'remove_post_custom_fields' );

add_filter('get_user_option_meta-box-order_post', 'one_column_for_all', 10, 1);
function one_column_for_all($option) {
    $result['normal'] = 'postexcerpt,formatdiv,trackbacksdiv,tagsdiv-post_tag,categorydiv,postimagediv,postcustom,commentstatusdiv,slugdiv,authordiv';
    $result['side'] = '';
    $result['advanced'] = '';
    return $result;
}
add_filter('get_user_option_meta-box-order_post','submitdiv_at_bottom', 999, 1);
function submitdiv_at_bottom($result){
    $result['normal'] .= ',submitdiv';
    return $result;
}

function remove_post_custom_fields() {
	remove_meta_box( 'postcustom' , 'post' , 'normal' ); 
	remove_meta_box( 'tagsdiv-post_tag' , 'post' , 'normal' );
}

function cd_meta_box_add(){
	if(function_exists('load_plugin_textdomain')) load_plugin_textdomain('stepsLB',PLUGINDIR.'/stepsLB');
	add_meta_box( 'my-meta-box-id', __( "Content", "stepsLB"), 'cd_meta_box_cb', 'post', 'side', 'high' );	
}

function cd_meta_box_cb( $post ){
	echo '
		<style>
			#step1{
				color: white;
				margin-top: 6px;
				padding-left: 41px;
				position:absolute;
				cursor: pointer;		
			}
			#step2{
				color: white;
				margin-top: 45px;
				padding-left: 40px;
				position: absolute;
				cursor: pointer;
			}
			#step3{
				color: white;
				margin-top: 85px;
				padding-left: 41px;
				position: absolute;
				cursor: pointer;
			}
			#step4{
				color: white;
				margin-top: 128px;
				padding-left: 41px;
				position: absolute;
				cursor: pointer;		
			}
			#step5{
				color: white;
				margin-top: 166px;
				padding-left: 41px;
				position: absolute;
				cursor: pointer;
			}
			#step6{
				color: white;
				margin-top: 211px;
				padding-left: 41px;
				position: absolute;
				cursor: pointer;
			}
			#step1:hover{color:rgb(214, 193, 182);}
			#step2:hover{color:rgb(214, 193, 182);}
			#step3:hover{color:rgb(214, 193, 182);}
			#step4:hover{color:rgb(214, 193, 182);}
			#step5:hover{color:rgb(214, 193, 182);}
			#step6:hover{color:rgb(214, 193, 182);}
			#steper{
				margin-bottom: 10px;
				margin-top: 10px;
				height:234px;
				width:100%;
				background: #0F659D url("../'.PLUGINDIR.'/stepsLB/steps2.png") repeat-y;
			}
			#helper{
				min-width:320px;
				min-height:240px;
				margin-top:-220px;
				margin-left:-325px;
				z-index:1000;
				position:absolute;
				-moz-box-shadow: 5px 7px 7px -3px #0F659D;
				-webkit-box-shadow: 5px 7px 7px -3px #0F659D;
				box-shadow: 5px 7px 7px -3px #0F659D;
				border-width: 1px;
				border-style:solid;
				border-color: #0F659D;
				background-color: #0F659D;
				display:none;
			}
			#titleHelper{
				background-color:#0F659D;
				color:#FFF;
				font-weight:bold;
				-moz-box-shadow: 0px 0px 5px 0px 1px #0F659D;
				-webkit-box-shadow: 0px 5px 0px 1px #0F659D;
				box-shadow: 0px 5px 0px 1px #0F659D;
				padding-left: 10px;
				height: 18px;
				border-bottom-width: 1px;
				border-bottom-style:solid;
				border-bottom-color: #FFF;
			}
		</style>
	<div id="steper" style="text-align:center;">
		<div id="step1" onmouseover="document.getElementById(\'helper\').style.display=\'inline\';document.getElementById(\'titleHelper\').innerHTML=\''.__( "Write a title", "stepsLB").'\';document.getElementById(\'imgHelper\').src=\''.get_option("siteurl").'/'.PLUGINDIR.'/stepsLB/mov1.gif\';" onmouseout="document.getElementById(\'helper\').style.display=\'none\';">'.__( "Write a title", "stepsLB").'</div>
		
		<div id="step2" onmouseover="document.getElementById(\'helper\').style.display=\'inline\';document.getElementById(\'titleHelper\').innerHTML=\''.__( "Upload Audio/Video", "stepsLB").'\';document.getElementById(\'imgHelper\').src=\''.get_option("siteurl").'/'.PLUGINDIR.'/stepsLB/mov2.gif\';" onmouseout="document.getElementById(\'helper\').style.display=\'none\';">'.__( "Upload Audio/Video", "stepsLB").'</div>
		
		<div id="step3" onmouseover="document.getElementById(\'helper\').style.display=\'inline\';document.getElementById(\'titleHelper\').innerHTML=\''.__( "Write content", "stepsLB").'\';document.getElementById(\'imgHelper\').src=\''.get_option("siteurl").'/'.PLUGINDIR.'/stepsLB/mov3.gif\';" onmouseout="document.getElementById(\'helper\').style.display=\'none\';">'.__( "Write content", "stepsLB").'</div>
		
		<div id="step4" onmouseover="document.getElementById(\'helper\').style.display=\'inline\';document.getElementById(\'titleHelper\').innerHTML=\''.__( "Scroll Down", "stepsLB").'\';document.getElementById(\'imgHelper\').src=\''.get_option("siteurl").'/'.PLUGINDIR.'/stepsLB/mov4.gif\';" onmouseout="document.getElementById(\'helper\').style.display=\'none\';">'.__( "Scroll Down", "stepsLB").'</div>
		
		<div id="step5" onmouseover="document.getElementById(\'helper\').style.display=\'inline\';document.getElementById(\'titleHelper\').innerHTML=\''.__( "Select a category", "stepsLB").'\';document.getElementById(\'imgHelper\').src=\''.get_option("siteurl").'/'.PLUGINDIR.'/stepsLB/mov5.gif\';" onmouseout="document.getElementById(\'helper\').style.display=\'none\';">'.__( "Select a category", "stepsLB").'</div>
		
		<div id="step6" onmouseover="document.getElementById(\'helper\').style.display=\'inline\';document.getElementById(\'titleHelper\').innerHTML=\''.__( "Publish", "stepsLB").'\';document.getElementById(\'imgHelper\').src=\''.get_option("siteurl").'/'.PLUGINDIR.'/stepsLB/mov6.gif\';" onmouseout="document.getElementById(\'helper\').style.display=\'none\';">'.__( "Publish", "stepsLB").'</div>
		
	</div>
	<div id="helper">
		<p id="titleHelper">Write content</p>
		<img id="imgHelper" src="" width="320px" height="240px" />
	</div>
	';
		
}
?>