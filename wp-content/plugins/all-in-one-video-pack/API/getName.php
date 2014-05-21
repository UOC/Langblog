<?php

error_reporting(E_ALL); 
ini_set( 'display_errors','1');


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

$entryId = $_GET["id"];
$version = null;

$result = $client->media->get($entryId, $version);


echo $result->name;

?>