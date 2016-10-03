<?php

function buildLive($id){
    $query_live = "SELECT idplace FROM Live WHERE idpers='$id'";
    $ret = $GLOBALS['db']->query($query_live);
    
    echo "<live>";
    
    while($e = $ret->fetch(PDO::FETCH_ASSOC)){
	echo "<idplace rid='" .$e["idplace"] . "' />";
    }
    
    echo "</live>";
}

function buildWorkplace($id){
    $query_work = "SELECT idwork FROM Workat WHERE idpers='$id'";
    $ret = $GLOBALS['db']->query($query_work);
    
    echo "<workat>";
    
    while ($e = $ret->fetch(PDO::FETCH_ASSOC)){
	echo "<idwork rid='" .$e["idwork"]. "' />";
    }
    
    echo "</workat>";
}

function buildPers($pers){
    echo "<pers uid='" .$pers["idstr"] ."' >";
    
    echo "<rname>" .$pers["rname"] ."</rname>";
    echo "<sexe>" .$pers["sexe"] ."</sexe>";
    echo "<birth>" .$pers["birth"] ."</birth>";
    echo "<lang>" .$pers["lang"] ."</lang>";
    
    
    buildLive($pers["id"]);
    buildWorkplace($pers["id"]);
    
    echo "</pers>";
}


function buildPersons(){
    $query_pers = "SELECT * FROM Pers";
    $ret = $GLOBALS['db']->query($query_pers) or die("HERE");

    
    echo "<Persons>";
    
    while($e = $ret->fetch(PDO::FETCH_ASSOC))
	buildPers($e);
    
    echo "</Persons>";
}

function buildFriend(){
    $query_friend = "SELECT * FROM Friend";
    $ret = $GLOBALS['db']->query($query_friend);
    
    echo "<Friends>";
    
    while($e = $ret->fetch(PDO::FETCH_ASSOC)){
	echo "<friend>";
	echo "<id1 rid='" .$e["idpers"] ."' />";
	echo "<id2 rid='" .$e["idfrd"] ."' />";
	echo "</friend>";
    }
    
    echo "</Friends>";
}

function buildPlaces(){
    $query_place = "SELECT * FROM Place";
    $ret = $GLOBALS['db']->query($query_place);
    
    echo "<Places>";
    
    while ($e = $ret->fetch(PDO::FETCH_ASSOC)){
	echo "<place uid='" .$e["id"] ."'>";
	echo "<pname>" .$e["pname"]. "</pname>";
	echo "<ptype>" .$e["typeent"]. "</ptype>";
	echo "</place>";
    }
    
    echo "</Places>";
    
}

function buildWorks(){
    $query_work = "SELECT * FROM Workplace";
    $ret = $GLOBALS['db']->query($query_work);
    
    echo "<Workplaces>";
    
    while($e = $ret->fetch(PDO::FETCH_ASSOC)){
	echo "<workplace id='" .$e["id"] ."' >";
	echo "<wname>" .$e["wname"]. "</wname>";
	echo "<wtype>" .$e["typeent"]. "</wtype>";
	echo "</workplace>";
    }
    
    echo "</Workplaces>";
    
}

function buildLike($id){
    $query_like = "SELECT pers FROM Phlike WHERE img='$id'";
    $ret = $GLOBALS['db']->query($query_like);
    
    echo "<likes>";
    
    while($e = $ret->fetch(PDO::FETCH_ASSOC)){
	echo "<id rid='" .$e["pers"]. "'/>";
    }
    
    echo "</likes>";
}

function buildPhoto($ph){
    echo "<photo uid='" .$ph["id"]. "'>";
    echo "<postby>" .$ph["postby"]. "</postby>";
    echo "<datep>" .$ph["datep"]. "</datep>";
    echo "<img>" .$ph["img"]. "<img>";
    
    buildLike($ph["id"]);
    
    echo "</photo>";
}

function buildPhotos(){
    $query_photo = "SELECT * FROM Photo";
    $ret = $GLOBALS['db']->query($query_photo);
    
    echo "<Photos>";
    
    while($e = $ret->fetch(PDO::FETCH_ASSOC)){
		buildPhoto($e);
    }
    
    echo "</Photos>";
}

///////////////////////////////////


include_once("utils.php");

coPDO();

echo "<?xml version='1.0' encoding='UTF-8' ?>";
echo "<Facebook>";

buildPersons();
buildFriend();
buildPlaces();
buildWorks();
buildPhotos();

echo "</Facebook>";

?>
