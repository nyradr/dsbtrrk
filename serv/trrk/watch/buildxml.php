<?php

function buildLive($id){
    $query_live = "SELECT idplace FROM Live WHERE idpers='$id'";
    $ret = mysql_query($query_live);
    
    echo "<live>";
    
    while($e = mysql_fetch_assoc($ret)){
	echo "<idplace rid='" .$e["idplace"] . "' />";
    }
    
    echo "</live>";
    
    mysql_free_result($ret);
}

function buildWorkplace($id){
    $query_work = "SELECT idwork FROM Workat WHERE idpers='$id'";
    $ret = mysql_query($query_work);
    
    echo "<workat>";
    
    while ($e = mysql_fetch_assoc($ret)){
	echo "<idwork rid='" .$e["idwork"]. "' />";
    }
    
    echo "</workat>";
    
    mysql_free_result($ret);
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
    $ret = mysql_query($query_pers) or die("HERE");

    
    echo "<Persons>";
    
    while($e = mysql_fetch_assoc($ret))
	buildPers($e);
    
    mysql_free_result($ret);
    
    echo "</Persons>";
}

function buildFriend(){
    $query_friend = "SELECT * FROM Friend";
    $ret = mysql_query($query_friend);
    
    echo "<Friends>";
    
    while($e = mysql_fetch_assoc($ret)){
	echo "<friend>";
	echo "<id1 rid='" .$e["idpers"] ."' />";
	echo "<id2 rid='" .$e["idfrd"] ."' />";
	echo "</friend>";
    }
    
    mysql_free_result($ret);
    
    echo "</Friends>";
}

function buildPlaces(){
    $query_place = "SELECT * FROM Place";
    $ret = mysql_query($query_place);
    
    echo "<Places>";
    
    while ($e = mysql_fetch_assoc($ret)){
	echo "<place uid='" .$e["id"] ."'>";
	echo "<pname>" .$e["pname"]. "</pname>";
	echo "<ptype>" .$e["typeent"]. "</ptype>";
	echo "</place>";
    }
    
    mysql_free_result($ret);
    
    echo "</Places>";
    
}

function buildWorks(){
    $query_work = "SELECT * FROM Workplace";
    $ret = mysql_query($query_work);
    
    echo "<Workplaces>";
    
    while($e = mysql_fetch_assoc($ret)){
	echo "<workplace id='" .$e["id"] ."' >";
	echo "<wname>" .$e["wname"]. "</wname>";
	echo "<wtype>" .$e["typeent"]. "</wtype>";
	echo "</workplace>";
    }
    
    echo "</Workplaces>";
    
    mysql_free_result($ret);
    
}

function buildLike($id){
    $query_like = "SELECT pers FROM Phlike WHERE img='$id'";
    $ret = mysql_query($query_like);
    
    echo "<likes>";
    
    while($e = mysql_fetch_assoc($ret)){
	echo "<id rid='" .$e["pers"]. "'/>";
    }
    
    echo "</likes>";
    
    mysql_free_result($ret);
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
    $ret = mysql_query($query_photo);
    
    echo "<Photos>";
    
    while($e = mysql_fetch_assoc($ret)){
		buildPhoto($e);
    }
    
    mysql_free_result($ret);
    
    echo "</Photos>";
}

///////////////////////////////////


include_once("utils.php");

connect();

echo "<?xml version='1.0' encoding='UTF-8' ?>";
echo "<Facebook>";

buildPersons();
buildFriend();
buildPlaces();
buildWorks();
buildPhotos();

echo "</Facebook>";

mysql_close();

?>
