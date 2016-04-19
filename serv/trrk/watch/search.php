<?php
include_once("utils.php");
include_once("draw.php");

$query = null;

if(isset($_GET["sub"])){
	$name = $_GET['rname'];
	
	if($name){
		$db = coPDO();
		
		$sql = 'SELECT idstr, rname FROM Pers WHERE rname LIKE ?'
        . ' UNION'
        . ' SELECT idstr, rname FROM Pers WHERE idstr LIKE ?'; 
		
		$query = $db->prepare($sql);
		//$query = $db->prepare("SELECT idstr, rname FROM Pers WHERE rname LIKE ?");
		
		$query->execute(array("%$name%", "%$name%"));
	}
}

?>
<!DOCTYPE html>
<html>
	<?php drawHead("Recherche d'une personne"); ?>
	<body>
		<?php drawHeader(); ?>
		<div id="search">
			<form action="search.php" method="get">
				<table>
					<tr>
						<td>Par nom</td>
						<td><input type="text" name="rname"></td>
					</tr>
				</table>
				<input type="submit" name="sub" value="Rechercher">
			</form>
		</div>
		<div id="result">
			<table>
				<?php
				if($query){
					while($e = $query->fetch(PDO::FETCH_ASSOC)){
						echo "<tr>";
						echo "<td><a href='persons.php?id=" .$e['idstr']. "'>" .$e['rname'] ."</a></td>";
						echo "</tr>";
					}
					
				}
				?>
			</table>
		</div>
	</body>
</html>
