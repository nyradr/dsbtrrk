<?php
/*	Server part of the facebook friend page extraction
	
	If the friend link already exist do nothings
	
	By nyradr : nyradr@protonmail.com
	
	TODO	: None
	STATUS	: Tested(Work), Check id inversion
*/

/*	Secure get post values
*/

include_once("utils.php");

if (	isset($_POST['id']) and
		isset($_POST['data'])	){

	$id = spost("id");
	$data = spost("data");
	
	$data = csv_toarray($data);
	
	//database connect
	connect();
	
	foreach($data as $dt){	//process all data (friend id)
		$idpers = getPersonId($id);	// get numeric id from fb id
		$idfrd = getPersonId($dt);
		
		if($idpers == null && $idfrd == null)	// nobody exist -> error
			echo "No link possible";
		
		if($idpers == null){		// add first person if she not exist
			$idpers = addPerson($id);
			echo "AjoutA : '$id'";
		}
		
		if($idfrd == null){	//add second person if she not exist
			$idfrd = addPerson($dt);
			echo "Ajout : '$dt'";
		}
		
		/*if($idfrd < $idpers){	// swap to the the smaller first (avoid doublons)
			$tmp = $idfrd;
			$idfrd = $idpers;
			$idpers = $tmp;
		}*/
		
		$sql = "INSERT INTO Friend(idpers, idfrd) VALUES ('$idpers', '$idfrd')";
		mysql_query($sql);
		
		echo "<br>";
	}
	
	mysql_close();
}else
	echo "Error : no post";
?>
<!--Debug-->
<!--
<html>
	<body>
		<form action="friend.php" method="post">
			<input type="input" name="id" value="type1">
			<input type="input" name="data" value="type2">
			<input type="submit" name="sub" value="Submit">
		</form>
	</body>
</html>
-->
