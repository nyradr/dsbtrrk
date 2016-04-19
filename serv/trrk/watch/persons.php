<?php
include_once("utils.php");
include_once("draw.php");

$personId = "";

if(	isset($_GET["id"])	){
	$personId = $_GET["id"];
	$infosUrl = "getpers.php?id=$personId";
}

?>
<!DOCTYPE html>
<html>
	<?php
		$headTitle = "Informations sur une personne";
		if($personId != "")
			$headTitle = "Informations sur $personId";
		drawHead($headTitle);
	?>
	<body>
		<?php drawHeader(); ?>
		<div id="root">
			<div id="hide">
				<link rel="stylesheet" href="css/persons.css">
				<!-- Begin sigma import-->
				<script src="sigma/sigma.min.js"></script>
				<!-- End sigma import -->
				<!-- import js person-->
				<script src="js/ajax.js"></script>
				<script src="js/persons.js"></script>
				<script src="js/CPers.js"></script>
				<!-- end person import -->
				
				
				<script>
					var personId = <?php echo "'$personId'"; ?>;
					
					person = new Pers(personId, false);
					selecPers = person;
					
				</script>
			</div>
			
			<div id="search">
				<form method="GET" action="persons.php">
					ID facebook exact de la personne a rechercher
					<input type="text" name="id">
					<input type="submit" value="voir">
					<div id="search-error">id inconnus</div>
					<script>visibleSearchError(person.xml, personId);</script>
				</form>
			</div>
			
			<div id="graph">
				<!-- sigma graph container-->
				<div id="graph-container">
				</div>
				<div id="graph-info">
					<div>
						Filtres : <br>
						Précision du graph (de 0 à <script>document.write(person.getMaxRef());</script>)
						<input id="graph-info-note" type="range" min=0 max=0 value=1>
						<span id="graph-info-note-val">(1)</span>
						<br>
						<input id="graph-info-friend" type="checkbox" onclick="applyNoteFilter();"> Amis<br>
						<input id="graph-info-liked" type="checkbox" onclick="applyNoteFilter();"> Like de la personne<br>
						<input id="graph-info-likeon" type="checkbox" onclick="applyNoteFilter();"> Like sur la personne<br>
					</div>
					<hr>
					<div id="graph-info-pinf"></div>
					
					<script>
						buildNoteRange("graph-info-note");
						var sig = buildSigma(person, "graph-container", onSigmaEvent);
						
						
						selecPers.drawPersInfo("graph-info-pinf");
					</script>
				</div>
				
			</div>
			<div id="refs">
				<!-- referenced persons -->
				REFS
				<script>
					buildRefs(person, "refs");
				</script>
			</div>
		</div>
	</body>
</html>
