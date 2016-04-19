<?php
/*	Add like link to the database
 * 
 * 	TODO : All
 * 	Status :  wait
*/

include_once("utils.php");

if(	isset($_POST["img"]) and
	isset($_POST["data"])	){
		
	$img = spost("img");
	$data = spost("data");
	
	$data = csv_toarray($data);
	
	connect();
	
	$phid = getImgId($img);
	
	if($phid){
		foreach($data as $like){
			$id = getPersonId($like);
			if($id == null)
				$id = addPerson($like);
		
			$query = "INSERT INTO Phlike(img, pers) VALUES('$phid', '$id')";
			mysql_query($query);
		}
	}
	
	mysql_close();
}

?>
<!--
<form action="like.php" method="post">
    <input type="text" name="img" value="img"><br>
    <input type="text" name="data" value="data"><br>
    <input type="submit" name="sub">
</form>
-->
