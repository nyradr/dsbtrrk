<?php
/*	Treating the about extraction from the plugin
	If the person don't exist she will be added to de data base
	
	By nyradr : nyradr@protonmail.com
	
	TODO 	: SQL injection correction
				Add the other informations when the moddule support it
	STATUS	: Tested (Work)
*/

function addLiving($livings, $id){
	foreach($livings as $l){
		$pid = getPlaceId($l);
		
		if($pid == null){
			$query_addpl = "INSERT INTO Place (pname) VALUES ('$l')";
			mysql_query($query_addpl);
			$pid = mysql_insert_id();
		}
		
		$query_live = "INSERT INTO Live (idpers, idplace) VALUES ('$id', '$pid')";
		mysql_query($query_live);
	}
}

function addWork($works, $id){
	foreach($works as $w){
		$wid = getWorkId($w);
		
		if($wid == null){
			$query_addw = "INSERT INTO Workplace (wname) VALUES ('$w')";
			mysql_query($query_addw);
			$wid = mysql_insert_id();
		}
		
		$query_work = "INSERT INTO Workat (idpers, idwork) VALUES ('$id', '$wid')";
		mysql_query($query_work);
	}
}

include_once("utils.php");

if (	isset($_POST['id']) and
		isset($_POST['name']) and
		isset($_POST['sexe']) and
		isset($_POST['birth']) and
		isset($_POST['living']) and
		isset($_POST['work']) and
		isset($_POST['lang'])	){
	    
	//get informations
	$id = spost('id');
	$name = spost('name');
	$sexe = spost('sexe');
	$birth = spost('birth');
	$lang = spost('lang');
	$living = spost('living');
	$work = spost('work');
	
	
	//process special data
	if ($sexe == "Homme")
		$sexe = 1;
	else
		$sexe = 0;
	
	$living = csv_toarray($living);
	$work = csv_toarray($work);
	$birth = extractValidDate($birth);
		
	/*	//Debug
	var_dump($id);
	var_dump($sexe);
	var_dump($birth);
	var_dump($lang);
	*/
	
	//database connect
	connect();
	
	
	
	if($idexist = getPersonId($id)){	//person not found -> adding    
	    $query_drop = "DELETE FROM Pers WHERE id='$idexist'";
	    mysql_query($query_drop);
	}
	
	$sql = "INSERT INTO Pers(idstr, rname, sexe, birth, lang) VALUES('$id', '$name', '$sexe', '$birth', '$lang')";
	mysql_query($sql) or die("Error SQL : " .mysql_error());
	
	$pid = mysql_insert_id();
	
	addLiving($living, $pid);
	addWork($work, $pid);
	
	mysql_close();
}else
	echo "Error : not a post";
?>

<!-- For the manual debug -->
<!--
<html>
	<body>
		<form action="about.php" method="post">
			<input type="text" name="id" value="id"><br>
			<input type="text" name="name" value="name"><br>
			<input type="text" name="sexe" value="sexe"><br>
			<input type="text" name="birth" value="birth"><br>
			<input type="text" name="living" value="liv"><br>
			<input type="text" name="work" value="work"><br>
			<input type="text" name="lang" value="lang"><br>
			
			<input type="submit" name="sub" value="Submit">
		</form>
	</body>
</html>
-->
