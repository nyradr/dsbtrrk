<?php
function drawHead($title){
	echo "<head>";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
	echo "<link rel='stylesheet' href='css/head.css'>";
	echo "<title>$title</title>";
	echo "</head>";
}

function drawHeader(){
	echo "<header><table><tr>";
	echo "<td><a href='statistics.php'>Statistiques</a></td>";
	
	echo "<td><a href='correlationLineaire.php'>analyse</a></td>";
	
	echo "<td><a href='search.php'>Recherche</a></td>";
	
	echo "<td><a href='persons.php'>informations</a></td>";
	
	
	echo "<td><a href='RechercheImages.php'>Recherche photos & likes</a></td>";
	
	echo "<td><a href='comparaisonPersonne.php'>Comparaison</a></td>";
	
	echo "<td><a href='buildxml.php'>xml</a></td>";
	
	echo "</tr></table></header>";
}

?>
