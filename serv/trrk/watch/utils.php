<?php

define("HOST", "host");
define("DB", "db");
define("USR", "usr");
define("MDP", "mdp");

function coPDO(){
	$GLOBALS['db'] = new PDO("mysql:host=" . HOST . ";dbname="  .DB, USR, MDP);
	
	return $GLOBALS['db'];
}

function spost($id){
    return $_POST[$id];
}

/*	Get database person ID in function of facebook id
 * 	return null if fb id not found
*/
function getPersonId($fbid){
    $sql = "SELECT id FROM Pers WHERE idstr= :id";
    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->bindParam(":id", $fbid);
    
    $stmt->execute();
    
    if($e = $stmt->fetch(PDO::FETCH_ASSOC))
		return $e['id'];
	
    return null;
}

function getPersonIdstr($id){
	$query = $GLOBALS['db']->prepare("SELECT idstr FROM Pers WHERE id= :id OR idstr= :id");
	$query->bindParam(":id", $id);
	
	$query->execute();
	
	if($e = $query->fetch(PDO::FETCH_ASSOC))
		return $e['idstr'];
		
	return null;
}

/*	Test if the ID is a database person ID
 * 	Return true id the id is in the database
*/
function isPersonId($id){
	$query = $GLOBALS['db']->prepare("SELECT id FROM Pers WHERE id= :id");
	$query->bindParam(":id", $id);
	
	$query->execute();
	
	return $query->rowCount() > 0;
}

function getPlaceId($fbid){
	$query = "SELECT id FROM Place WHERE pname= :id";
    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->bindParam(":id", $fbid);
    
    $stmt->execute();
    
    if($e = $stmt->fetch(PDO::FETCH_ASSOC))
		return $e['id'];
	
    return null;
}

function getWorkId($fbid){
	$query = "SELECT id FROM Workplace WHERE wname= :id";
    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->bindParam(":id", $fbid);
    
    $stmt->execute();
    
    if($e = $stmt->fetch(PDO::FETCH_ASSOC))
		return $e['id'];
	
    return null;
}

function getImgId($url){
	
	$query = "SELECT id FROM Photo WHERE img= :url";
	$stmt = $GLOBALS['db']->prepare($sql);
	$stmt->bindParam(":url", $url);
	
	$stmt->execute();
	
	if($e = $stmt->fetch(PDO::FETCH_ASSOC))
		return $e["id"];
		
	return null;
}

?>
