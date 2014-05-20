<?php
require_once("KalturaClient.php");
require_once("config.php");
$idVideo=$_GET["idVideo"];
$type = null;
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$resultKs = $client->session->start($secret, $userId, $type, $partnerId, $expiry, $privileges);
$client->setKs($resultKs);
$entryId = $idVideo;
$flv = $client->media->get($idVideo);
echo $flv->id;
?>