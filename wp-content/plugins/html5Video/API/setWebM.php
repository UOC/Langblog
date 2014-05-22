<?php

require_once('KalturaClient.php');
require_once('config.php');
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$secret = $secretAdmin;
$userId = null;
$type = KalturaSessionType::ADMIN;
$expiry = null;
$privileges = null;
$result = $client->session->start($secret, $userId, $type, $partnerId, $expiry, $privileges);
$client->setKs($result);
$entryId = $_GET["idVideo"];
$flavorParamsId = $_GET["typeFlavor"];
$priority = null;
$result = $client->flavorAsset->convert($entryId, $flavorParamsId, $priority);
?>
