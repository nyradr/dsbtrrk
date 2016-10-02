<?php
/*	Get unique person information on database
 * 
 * 	$query_friend = "SELECT Pers.id FROM Pers, Friend WHERE (Pers.id=Friend.idpers AND Friend.idfrd='$id') OR (Pers.id=Friend.idfrd AND Friend.idpers='$id')";
 * 	$query_liked = "SELECT Photo.postby AS id, COUNT(*) AS nbr FROM Photo, Phlike WHERE (Photo.id=Phlike.img AND Phlike.pers='$id') GROUP BY Photo.postby";
 * 	$query_likeon = "SELECT Phlike.pers AS id, COUNT(*) AS nbr FROM Phlike, Photo WHERE (Phlike.img=Photo.id AND Photo.postby='$id') GROUP BY Phlike.pers";
 * 	$query_info = "SELECT * FROM Pers WHERE idstr='$id'";
 */
include_once("utils.php");

class Ref{
	private $id;
	private $friend;
	private $likeon;
	private $liked;
	
	public function __construct($id){
		$this->id = $id;
		$this->friend = 0;
		$this->likeon = 0;
		$this->liked = 0;
	}
	
	public function setFriend($f){
		$this->friend = $f;
	}
	
	public function setLikeon($n){
		$this->likeon = $n;
	}
	
	public function setLiked($n){
		$this->liked = $n;
	}
	
	private function getTotal(){
		return $this->friend + $this->likeon + $this->liked;
	}
	
	public function drawXML(){
		echo "<ref id='" .$this->id. "' total='" .$this->getTotal(). "' >";
		echo "<friend friend='" .$this->friend ."'/>";
		echo "<likeon>" .$this->likeon. "</likeon>";
		echo "<liked>" .$this->liked. "</liked>";
		echo "</ref>";
	}
}

class PersonRef{
	
	private $light;
	
	private $id;
	private $idstr;
	private $name;
	private $birth;
	private $sexe;
	private $lang;
	
	private $referenced;
	
	private $places;
	private $works;
	
	public function __construct($id, $light){
		$this->light = $light;
		
		$this->id = $id;
		
		$this->getInfos();
		
		if(!$light){
			$this->getFriend();
			$this->getLiked();
			$this->getLikeon();
			
			$this->getPlaces();
			$this->getWorks();
		}
	}
	
	private function getInfos(){
		$id = $this->id;
		$query_info = $GLOBALS['db']->prepare("SELECT * FROM Pers WHERE id= :id");
		$query_info->bindParam(":id", $id);
		
		$query_info->execute();
		
		if($e = $query_info->fetch()){
			$this->idstr = $e["idstr"];
			$this->name = $e["rname"];
			$this->birth = $e["birth"];
			$this->sexe = $e["sexe"];
			$this->lang = $e["lang"];
		}
	}
	
	// obtient les amis de la personne
	private function getFriend(){
		$id = $this->id;
		$query_friend = $GLOBALS['db']->prepare("SELECT Pers.id FROM Pers, Friend WHERE (Pers.id = Friend.idpers AND Friend.idfrd = :id) OR (Pers.id = Friend.idfrd AND Friend.idpers = :id)");
		$query_friend->bindParam(":id", $id);
		
		$query_friend->execute();
		
		while($e = $query_friend->fetch(PDO::FETCH_ASSOC)){
			if (!isset($this->referenced[$e["id"]]))
				$this->referenced[$e["id"]] = new Ref($e["id"]);
				
			$this->referenced[$e["id"]]->setFriend(1);
		}
	}
	
	// obtient les likes de la personne sur les photos d'autres personnes
	private function getLiked(){
		$sql = 'SELECT Photo.postby AS id, COUNT(*) AS nbr FROM Photo WHERE Photo.id IN ('
        . ' SELECT Phlike.img FROM Phlike WHERE Phlike.pers = :id'
        . ' )'
        . ' GROUP BY Photo.postby';
        
		$query_liked = $GLOBALS['db']->prepare($sql);
		$query_liked->bindParam(":id", $this->id);
		
		$query_liked->execute();
		
		while($e = $query_liked->fetch(PDO::FETCH_ASSOC)){
			if (!isset($this->referenced[$e["id"]]))
				$this->referenced[$e["id"]] = new Ref($e["id"]);
				
			$this->referenced[$e["id"]]->setLiked($e["nbr"]);
		}
	}
	
	// obtient les likes de personnes externes sur les photo de la personne
	private function getLikeon(){
		$sql = 'SELECT Phlike.pers AS id, COUNT(*) AS nbr FROM Phlike WHERE Phlike.img IN ('
        . ' SELECT Photo.id FROM Photo WHERE Photo.postby= :id'
        . ' )'
        . ' GROUP BY Phlike.pers'; 
		
		$query_likeon = $GLOBALS['db']->prepare($sql);
		$query_likeon->bindParam(":id", $this->id);
		
		$query_likeon->execute();
		
		while($e = $query_likeon->fetch(PDO::FETCH_ASSOC)){
			if (!isset($this->referenced[$e["id"]]))
				$this->referenced[$e["id"]] = new Ref($e["id"]);
				
			$this->referenced[$e["id"]]->setLikeon($e["nbr"]);
		}
	}
	
	private function getPlaces(){
		$query = $GLOBALS['db']->prepare("SELECT Place.pname FROM Place INNER JOIN Live ON Place.id = Live.idplace WHERE Live.idpers= :id");
		$query->bindParam(":id", $this->id);
		$query->execute();
		
		$this->places = array();
		
		while($e = $query->fetch(PDO::FETCH_ASSOC))
			$this->places[] = $e["pname"];
	}
	
	private function getWorks(){
		$query = $GLOBALS['db']->prepare("SELECT Workplace.wname FROM Workplace INNER JOIN Workat ON Workplace.id = Workat.idwork WHERE Workat.idpers= :id");
		$query->bindParam(":id", $this->id);
		$query->execute();
		
		$this->works = array();
		
		while($e = $query->fetch(PDO::FETCH_ASSOC))
			$this->works[] = $e["wname"];
	}
	
	public function drawXML(){
		echo "<pers id='" .$this->id. "' idstr='" .$this->idstr. "' >";
		
		echo "<name>" .$this->name. "</name>";
		echo "<sexe>" .$this->sexe. "</sexe>";
		echo "<birth>" .$this->birth. "</birth>";
		echo "<lang>" .$this->lang. "</lang>";
		
		if(!$this->light){	
			echo "<referenced>";
			foreach($this->referenced as $k=>$v){
				$v->drawXML();
			}
			echo "</referenced>";
			
			echo "<places>";
			foreach($this->places as $k=>$v)
				if(strlen($v) > 0)
					echo "<place>$v</place>";
			echo "</places>";
			
			echo "<works>";
			foreach($this->works as $k=>$v)
				if(strlen($v) > 0)
					echo "<work>$v</work>";
			
			echo "</works>";
		}
		
		echo "</pers>";
	}
	
}

if(	isset($_GET["id"])	){
	coPDO();
	
	$id = $_GET["id"];
	if(!isPersonId($id))
		$id = getPersonId($_GET["id"]);
	
	if($id){
		$pers = new PersonRef($id, isset($_GET["light"]));
		
		echo "<?xml version='1.0' encoding='UTF-8'?>";
		echo "<!DOCTYPE pers SYSTEM 'dtd/getpers.dtd'>";
		$pers->drawXML();
	}else
		echo "NOTHING";
}else
	echo "NOTHING";

?>

