<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

define('DOING_AJAX', true);
//define('WP_USE_THEMES', false);
require_once('../../../../../wp-load.php');

//error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$upload_handler = new UploadHandler();
/*
require_once('../../lib/kaltura_html5_helpers.php');
		
KalturaHTML5Helpers::force200Header();
*/