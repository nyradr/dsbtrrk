<!DOCTYPE html>
<?php
include_once("utils.php");
include_once("draw.php");

connect();

$sql = "SELECT COUNT(*) FROM Pers";
$totalPers = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Pers WHERE birth IS NOT NULL";
$fullScan = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Friend";
$friends = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Photo";
$photos = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Phlike";
$likes = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Place";
$places = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Live";
$live = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Workplace";
$workplace = mysql_result(mysql_query($sql), 0);
		
$sql = "SELECT COUNT(*) FROM Workat";
$workat = mysql_result(mysql_query($sql), 0);

$sql = "SELECT AVG(moy) FROM (SELECT Photo.postby, COUNT(Photo.postby) AS moy FROM Photo GROUP BY Photo.postby) a";
$moyPhoto = round(mysql_result(mysql_query($sql), 0));

$sql = 'SELECT COUNT(*) FROM Photo WHERE'
        . ' Photo.postby NOT IN'
        . ' (SELECT Phlike.pers FROM Phlike)';
$phwl = mysql_result(mysql_query($sql), 0);

mysql_close();
?>

<html>
	<?php drawHead("Statistiques générales"); ?>
	<body>
		<?php drawHeader(); ?>
		<div>
			<h1>Informations sur la base</h1>
			<h3>MCD</h3>
			<img src='img/mcd.png' alt='MCD'>
			<h3>MLD</h3>
			<img src='img/MLD.png' alt='MLD'>
			
		</div>
		<div>
			<h1>Statistiques :</h1>
			<?php
			echo "Le scan complet de $fullScan personnes à permis de référencer :<br>";
			echo "<ul>";
			echo "<li>$totalPers personnes</li>";
			echo "<li>$friends amis</li>";
			echo "<li>$photos photos, avec une moyenne de $moyPhoto photos par personnes</li>";
			echo "<li>$likes likes sur les photos, $phwl photo(s) sont sans like(s)</li>";
			echo "<li>$live lieux dont $places lieux uniques</li>";
			echo "<li>$workat lieux de travails (entreprises, écoles, ...) dont $workplace uniques</li>";
			echo "</ul>";
			echo "soit un total de " .($totalPers + $friends + $photos + $likes + $places + $live + $workplace + $workat). " entrées";
			?>
		</div>
		
	</body>
</html>
