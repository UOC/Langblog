<?php
require_once("KalturaClient.php");
require_once("config.php");

$id=$_GET["id"];
$bloginfo=$_GET["bloginfo"];
$typeaula=$_GET["typeaula"];
$semestre = $_GET["blogSemestre"];

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

//se define la categoria original
$category = new KalturaCategory();

//get Parent
$filter = new KalturaCategoryFilter();
$filter->parentIdEqual = 0;
$results = $client->category->listAction($filter, $pager);
$results = $results->objects;
//busca la cat del semestre
foreach($results as $obj){
	$results = $client->category->get($obj->id);
	if($results->name == $typeaula){ 
		$parentCat = $results->id;
		$foundType=true;
	}
}

//crea la cat del typeaula si no existe
if($foundType==false){
	$category->parentId = 0;
	$category->name = $typeaula;
	$results = $client->category->add($category);
	$pIdType = $client->category->get($results->id);
	$parentCat = $category->parentId = $pIdType->id;
}

//get Parent typeAula
$filter = new KalturaCategoryFilter();
$filter->parentIdEqual = $parentCat;
$results = $client->category->listAction($filter, $pager);
$results = $results->objects;
//busca la cat del semestre
foreach($results as $obj){
	$results = $client->category->get($obj->id);
	if($results->name == $semestre){ 
		$pIdSem = $results->id;
		$foundSem=true;
	}
}

//crea la cat del semestre si no existe
if($foundSem==false){
	$category->parentId = $parentCat;
	$category->name = $semestre;
	$results = $client->category->add($category);
	$pIdSem = $client->category->get($results->id);
	$pIdSem = $pIdSem->id;
}

//get Parent Semestre
$filterB = new KalturaCategoryFilter();
$filterB->parentIdEqual = $pIdSem;
$resultsB = $client->category->listAction($filterB, $pager);
$resultsB = $resultsB->objects;
//busca la categoria del blog actual
foreach($resultsB as $objB){
	$resultsB = $client->category->get($objB->id);
	if($resultsB->name == $bloginfo){ 
		$pIdBlog = $resultsB->id;
		$foundBlog=true;
	}
}

//crea la cat del blog si no existe
if($foundBlog==false){
	$category->parentId = $pIdSem;
	$category->name = $bloginfo;
	$results = $client->category->add($category);
	$pIdBlog = $client->category->get($results->id);
	$pIdBlog = $pIdBlog->id;
}

//Añade el video a esa categoria
$categoryEntry = new KalturaCategoryEntry();
$categoryEntry->categoryId = $pIdBlog;
$categoryEntry->entryId = $id;
try{
	$results = $client->categoryEntry->add($categoryEntry);
} catch (Exception $ex)  
	{  
	 echo $ex->getMessage();
	}
?>