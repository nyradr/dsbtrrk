<?php

function connect(){
    /// OPEN MYSQL CONNEXTION
}

//	extract data from csv list, separator will be '/'
function csv_toarray($data){
	$lst = array();
	$il = 0;
	$tmp = "";
	
	for($i = 0; $i < strlen($data); $i++){
		if($data[$i] == "\\"){
			$lst[$il] = $tmp;
			$il++;
			$tmp = "";
		}else
			$tmp .= $data[$i];
	}
	
	if($tmp != "")
		$lst[$il] = $tmp;
	
	return $lst;
}


function dhash($data){
    return hash("sha256", $data);
}

function idfromstr($str){
	$id = 0;
}

function spost($id){
    return $_POST[$id];
}

/*	Get database person ID in function of facebook id
 * 	return null if fb id not found
*/
function getPersonId($fbid){
    $query = "SELECT id FROM Pers WHERE idstr='$fbid'";
    $ret = mysql_query($query);
    
    if($e = mysql_fetch_assoc($ret))
		return $e['id'];
	
    return null;
}

function getPlaceId($fbid){
	$query = "SELECT id FROM Place WHERE pname='$fbid'";
    $ret = mysql_query($query);
    
    if($e = mysql_fetch_assoc($ret))
		return $e['id'];
	
    return null;
}

function getWorkId($fbid){
	$query = "SELECT id FROM Workplace WHERE wname='$fbid'";
    $ret = mysql_query($query);
    
    if($e = mysql_fetch_assoc($ret))
		return $e['id'];
	
    return null;
}

function getImgId($url){
	
	$query = "SELECT id FROM Photo WHERE img='$url'";
	$ret = mysql_query($query);
	
	if($e = mysql_fetch_assoc($ret))
		return $e["id"];
		
	return null;
}

function delAccent($str){
	$ret = "";
	
	for($i = 0; $i < strlen($str); $i++){
		var_dump(ord($str[$i]));
		var_dump(ord("é"));
		
		if($str[$i] == 'é')
			$ret .= 'e';
		else
			$ret .= $str[$i];
			
		var_dump($ret[$i]);
		echo "<br>";
	}
	
	return $ret;
}

function extractValidDate($dt){
	$cte_month = array(	"janvier" => "january", "février" => "february",
						"mars" => "march", "avril" => "april",
						"mai" => "mai", "juin" => "june",
						"juillet" => "july", "aout" => "august",
						"septembre" => "september", "octobre" => "october",
						"novembre" => "november", "décembre" => "december");
	
	$dt = strtolower($dt);
	
	foreach($cte_month as $k => $v)
		$dt = str_replace($k, $v, $dt);
	
	$pdate = date_parse($dt);
	$now = getdate();
	
	if($pdate['year'] == 0)
		$pdate['year'] = $now['year'];
	
	if($pdate['month'] == 0)
		$pdate['month'] = $now['mon'];
		
	if($pdate['day'] == 0)
		$pdate['day'] = $now['mday'];
	
	$date = $pdate['year'] ."-". $pdate['month'] ."-". $pdate['day'];
	
	return $date;
}

function addPerson($idstr){	
	$query_insert = "INSERT INTO Pers (idstr) VALUES ('$idstr')";
	mysql_query($query_insert);
	return mysql_insert_id();
}

?>
