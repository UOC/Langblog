<?php
require_once("KalturaClient.php");
require_once("config.php");
$idVideo=$_GET["idVideo"];
$typeFlavor=$_GET["typeFlavor"];
$type = null;
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$resultKs = $client->session->start($secret, $userId, $type, $partnerId, $expiry, $privileges);
$client->setKs($resultKs);
$entryId = $idVideo;
$flv = $client->flavorAsset->getFlavorAssetsWithParams($entryId);

$found=false;
$i=0;
while($i<13 && $found==false){
	if($flv[$i]->flavorAsset->fileExt==$typeFlavor){
		echo $flv[$i]->flavorAsset->id;
		$found=true;
	}
	$i++;
}
?>