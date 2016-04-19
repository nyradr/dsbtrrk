<?php
include_once("utils.php");

function extractIds($str){
	$ids = Array();
	$iids = 0;
	$tmp = "";
	
	for($i = 0; $i < strlen($str); $i++){
		if($str[$i] == ','){
			$ids[$iids++] = $tmp;
			$tmp = "";
		}else
			$tmp .= $str[$i];
	}
	
	$ids[$iids] = $tmp;
	
	return $ids;
}

if(	isset($_GET["id"])){
	
	coPDO();
	
	$ids = $_GET["id"];
	$ids = extractIds($ids);
	
	echo "<?xml version='1.0' encoding='UTF-8' ?>";
	echo "<!DOCTYPE idstr SYSTEM 'dtd/getpersidstr.dtd'>";
	echo "<idstr>";
	
	foreach($ids as $id){
		$idstr = getPersonIdstr($id);
		
		if($idstr)
			echo "<id id='$id'>$idstr</id>";
	}
	
	echo "</idstr>";
}

?>
