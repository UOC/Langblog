<?php
//BLTI
$required_class = 'IMSBasicLTI/uoc-blti/bltiUocWrapper.php';
$exists=fopen ( $required_class, "r",1 );
   if (!$exists) {
       error_log('Required classes BASIC LTI not exists check the include_path is correct');
       echo('Required classes BASIC LTI not exists check the include_path is correct');
       return false;
   }
   include_once($required_class); 
   $context = new bltiUocWrapper(false, false);
   if ( ! $context->valid ) {
       error_log('LTI Authentication Failed, not valid request (make sure that consumer is authorized and secret is correct)');
       echo('LTI Authentication Failed, not valid request (make sure that consumer is authorized and secret is correct)');
       return;
    }
    $username = $context->getUserKey();
    //Set true or false by configuration to get the real username or not (uoc.edu:USER.33064)
    $basic_lti_custom_username = true;
    //Set true or false by configuration to get the real username or not (uoc.edu:USER.33064)
    $blti_custom_username = 'custom_username';
 
       if ($basic_lti_custom_username && strlen($blti_custom_username)>0) {
           if (isset($context->info[$blti_custom_username]) && strlen($context->info[$blti_custom_username])>0) {
           $username = $context->info[$blti_custom_username];
            }
       }
       $username = str_replace(':','-',$identifier);  // TO make it past sanitize_user
       $name = $context->getUserName();
       $email = $context->getUserEmail();
       $image = $context->getUserImage();
       $userid = $context->info['user_id'];
       //Add Session ID
       $sessionId = '';
       if (isset($context->info['custom_sessionid']) && strlen($context->info['custom_sessionid'])>0) {
           $sessionId = $context->info['custom_sessionid'];
        }
       //Get course data
       $course_key = $context->getCourseKey();
       $course_name = $context->getCourseName();
       $domain_id = $context->info['custom_domain_id'];
       $resource_key = $context->getResourceKey(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php 
  require_once("API/KalturaClient.php");
  require_once("API/config.php");
?>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
</head> 
<body>
<?php
$partnerUserID = $userID;
$type = null;
$expiry = null;
$privileges = null;
$typeA="aulas";
$type="aula";
$sem=base64_decode($_REQUEST["custom_sem"]);
$protocol = base64_decode($_REQUEST["custom_prot"])."://";
$idhtml = base64_decode($_REQUEST["custom_idhtml"]);

$idAsig=$course_key; 
$config = new KalturaConfiguration($partnerId);
$client = new KalturaClient($config);
$ks = $client->session->start($secret, $partnerUserID, $type, $partnerId, $expiry, $privileges);
$flashVars = array();
$flashVars["uid"] = $partnerUserID;
$flashVars["partnerId"] = $partnerId;
$flashVars["ks"] = $ks;
$flashVars["afterAddEntry"] = "onContributionWizardAfterAddEntry";
$flashVars["close"] = "onContributionWizardClose";
$flashVars["showCloseButton"] = false; 
$flashVars["Permissions"] = 1;
$flashVars["showcategories"] = false;
$flashVars["showtags"] = false;
$flashVars["showdescription"] = false;
?>
<h4 style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><?php echo $name;?></h4>
<div id="kcw" style="text-align:center"></div>
<script type="text/javascript">
var params = {
        allowScriptAccess: "always",
        allowNetworking: "all",
        wmode: "opaque"
};
var flashVars = <?php echo json_encode($flashVars); ?>;


swfobject.embedSWF("<?php echo $protocol;?>www.kaltura.com/kcw/ui_conf_id/9191921 ", "kcw", "680", "360", "9.0.0", "expressInstall.swf", flashVars, params);
</script>
<script type="text/javascript">
  var idVideo;
  function onContributionWizardAfterAddEntry(entries) {
    for(var i = 0; i < entries.length; i++) {
      idVideo = entries[i].entryId;
    }
  }
</script>
<script type="text/javascript">
  function getTitle(idVideo){
      $.get('API/getName.php?id='+idVideo, function(data){
        window.opener.getVideoId(idVideo, '<?php echo $idhtml;?>', data);
        window.close();
      });
  }
  function onContributionWizardClose() {
    $.ajax({
      type: 'GET',
      url: 'API/setCategory.php?id='+idVideo+'&bloginfo=<?php echo $idAsig;?>&typeaula=<?php echo $typeA;?>&blogSemestre=<?php echo $sem;?>&nombreTheme=<?php echo $type;?>',
      data: {},
      dataType: "xml",
      success: function(){
        getTitle(idVideo);
      }
    });
  }
  </script>
</body>
</html>
