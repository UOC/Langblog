<?php

require_once('KalturaClient.php');
require_once('config.php');
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
$results = $client->media->get($_GET["id"],0);
$tipo = $results->mediaType;

echo $tipo;
?>