<?php
switch($_GET["blog"]){
	case "UOC":	header("location:http://pretoria.uoc.es/wpmu/suport_cat/category/calendario/feed/");break;
	
	case "UOC2000":	header("location:http://pretoria.uoc.es/wpmu/suport_es/category/calendario/feed/");break;
	
	case "GCUOC": header("location:http://pretoria.uoc.es/wpmu/suport_en/category/calendario/feed/");break;
	
	default: header("location:http://pretoria.uoc.es/wpmu/suport_".$_GET["blog"]."/category/calendario/feed/");break;
}
?>