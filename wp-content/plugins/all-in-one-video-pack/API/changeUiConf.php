<?php
require_once("KalturaClient.php");
require_once("config.php");


$type = null;
$expiry = null;
$privileges = null;
$categoryEntry = null;
$pIdSem = null;
$pIdBlog = null;
$foundSem=false;
$foundBlog=false;
$foundType=false;
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$resultKs = $client->session->start($secret, $userId, $type, $partnerId, $expiry, $privileges);
$client->setKs($resultKs);


$id = 8938751;

$uiConf = new KalturaUiConf();
$uiConf->name = 'moveTabs';
$uiConf->description = 'move Upload Tabs';
$uiConf->objType = KalturaUiConfObjType::CONTRIBUTION_WIZARD;
$uiConf->confFile = '<kcw>
  <UIConfigList>
    <UIConfig>
      <target>ContributionWizard.swf</target>
      <cssUrl id="light_new_2.1.5" name="New Light(version 2.1.5 and up)">/flash/kcw/v2.1.9.1_mic/style.swf</cssUrl>
      <localeUrl id="en_US_kaltura_2.1.5" name="English US(version 2.1.5 and up)">/flash/kcw/v2.1.9.1_mic/en_US_ContributionWizard_kaltura.swf</localeUrl>
    </UIConfig>
  </UIConfigList>
  <ImportTypesConfig>
    <taggingConfig>
      <minTitleLen>1</minTitleLen>
      <maxTitleLen>2000</maxTitleLen>
      <minTagsLen>0</minTagsLen>
      <maxTagsLen>2000</maxTagsLen>
    </taggingConfig>
  </ImportTypesConfig>
  <webcamParams>
    <keyFrameInterval/>
    <width/>
    <height/>
    <framerate/>
    <favorArea/>
    <bandwidth/>
    <quality/>
  </webcamParams>
  <mediaTypes>
    <media type="video">
      <provider id="webcam" name="webcam" code="2">
        <authMethodList>
          <authMethod type="1"/>
        </authMethodList>
        <moduleUrl>WebcamView.swf</moduleUrl>
        <customData>
          <serverUrl>rtmp://{HOST_NAME}/oflaDemo</serverUrl>
        </customData>
      </provider>
	  <provider id="upload" name="upload" code="1">
        <authMethodList>
          <authMethod type="1"/>
        </authMethodList>
        <moduleUrl>UploadView.swf</moduleUrl>
        <fileFilters>
          <filter type="video">
            <allowedTypes>flv,asf,qt,mov,mpg,mpeg,avi,wmv,mp4,m4v,3gp</allowedTypes>
          </filter>
        </fileFilters>
      </provider>
    </media>
    <media type="audio">
 	<provider id="mic" name="mic" code="5">
        <authMethodList>
          <authMethod type="1" />
        </authMethodList>
        <moduleUrl>MicView.swf</moduleUrl>
        <customData>
          <serverUrl>rtmp://{HOST_NAME}/oflaDemo</serverUrl>
          <providerName>Microphone</providerName>
         </customData>
    </provider>
	<provider id="upload" name="upload" code="1">
        <authMethodList>
          <authMethod type="1"/>
        </authMethodList>
        <moduleUrl>UploadView.swf</moduleUrl>
        <fileFilters>
          <filter type="audio">
            <allowedTypes>flv,asf,wmv,qt,mov,mpg,avi,mp3,wav</allowedTypes>
          </filter>
        </fileFilters>
      </provider>
    </media>
    <media type="image">
      <provider id="upload" name="upload" code="1">
        <authMethodList>
          <authMethod type="1"/>
        </authMethodList>
        <moduleUrl>UploadView.swf</moduleUrl>
        <fileFilters>
          <filter type="image">
            <allowedTypes>jpg,bmp,png,gif,tiff</allowedTypes>
          </filter>
        </fileFilters>
      </provider>
    </media>
  </mediaTypes>
  <limitations>
    <upload>
      <singleFileSize min="-1" max="-1"/>
      <numFiles min="-1" max="1"/>
      <totalFileSize min="-1" max="-1"/>
    </upload>
    <search>
      <numFiles min="-1" max="-1"/>
    </search>
  </limitations>
  <StartupDefaults>
    <SingleContribution>true</SingleContribution>
    <enableTOU >false</enableTOU >
	<forceTOU>false</forceTOU>
    <autoTOUConfirmation>false</autoTOUConfirmation>
    <showLogoImage>false</showLogoImage>
    <alwaysShowPermission>false</alwaysShowPermission>
    <NavigationProperties>
      <showConfirmButtons>true</showConfirmButtons>
      <showCloseButton>true</showCloseButton>
      <enableIntroScreen>false</enableIntroScreen>
      <enableTagging>true</enableTagging>
    </NavigationProperties>
    <gotoScreen>
      <mediaType>video</mediaType>
      <mediaProviderName>webcam</mediaProviderName>
    </gotoScreen>
  </StartupDefaults>
</kcw>';
$uiConf->creationMode = KalturaUiConfCreationMode::ADVANCED;
$results = $client->uiConf->update($id, $uiConf);


var_dump($results);

?>