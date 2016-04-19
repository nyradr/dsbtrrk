<?php
/*	Add photo information to the data base
 * 
 * 	TODO : All :(
 * 	STATUS : Dev
*/

include_once('utils.php');


if(	isset($_POST['idpers']) and
	isset($_POST['date']) and
	isset($_POST['img'])	){
	
	$idpers = spost("idpers");
	
	$date = spost("date");
	$date = extractValidDate($date);
	$img = spost("img");
	
	connect();
	
	if($idpers = getPersonId($idpers)){
		$query_photo = "INSERT INTO Photo (postby, datep, img) VALUES ('$idpers', '$date', '$img')";
		mysql_query($query_photo) or die("ERROR " . mysql_error());
	}
	
	mysql_close();
}else
	echo "POST";

?>
<!--
<form action="photo.php" method="post">
    <input type="text" name="idpers"><br>
    <input type="text" name="date"><br>
    <input type="text" name="img"><br>
    <input type="submit" name="sub">
</form>
-->
