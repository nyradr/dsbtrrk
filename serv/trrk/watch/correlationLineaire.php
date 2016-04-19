<?php
require_once "MathMLMarkupGenerator.php";
require_once "TwoVarCorrelationPageGenerator.php";
require_once "StatisticsTools.php";
require_once "utils.php";
require_once "draw.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
		<link rel='stylesheet' href='css/head.css'>
        <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    </head>
    <body>
	<?php drawHeader(); ?>
	<h2>Calculs de corrélation</h2>
    <p>
        Depuis des séries numériques récupérées via des calculs d'agrégats dans la BDD :
        <ul>
            <li><a href="correlationLineaire.php?code=1"> Corrélation entre le nombre de photos publiées et le nombre de likes </a></li>
            <li><a href="correlationLineaire.php?code=2"> Corrélation entre le nombre de likes et le nombre d'amis </a></li>
            <li><a href="correlationLineaire.php?code=3"> Corrélation entre le nombre de photos publiées et le nombre d'amis </a></li>
        </ul>
    </p>
    <p>
        Depuis des séries numériques codées en dur, récupérées manuellement :
        <ul>
            <li><a href="correlationLineaire.php?code=4"> Corrélation entre le nombre de photos publiées et le nombre d'amis </a></li>
            <li><a href="correlationLineaire.php?code=5"> Corrélation entre le nombre de photos publiées et le nombre de mention j'aime </a></li>
        </ul>
    </p>
    <?php
    test();
    ?>
    </body>
    </html>


<?php


function test() {
    
    $nombreAmis =         array(355,  71, 371, 289, 77, 469, 34, 24, 74, 181, 70, 202,  50, 342, 259, 172,  70, 304, 244, 382, 22, 49, 166, 594, 113, 104, 62, 74, 228, 388, 111);
    $nombreDePhotos =     array(322, 311, 401, 586, 41, 665,  2,  1, 28,  40, 23, 208, 156,  40, 190, 101, 556, 356, 120,   0,  5, 18,  86, 341,  66, 104, 41, 35,  72, 109, 150);
    $nombreMentionJaime = array(143,  67, 102, 339, 19,   0, 13,  0,  0, 144,  0,  50,   0,   4, 112,  13,  83,  50, 198,   3,  1, 11,  29, 218,  0,  94,  50, 172, 146 , 127,  79);


    if (isset($_GET['code'])) {
		coPDO();
        switch ($_GET['code']) {
            case 1:
               displayCorrelationNombreDeLikeNombrePhoto();
               break;
            case 2:
               displayCorrelationNombreDeLikeNombreAmis();
               break;
            case 3:
               displayCorrelationNombreDePhotosNombreAmis();
               break;
            case 4:
               TwoVarCorrelationPageGenerator::displayCorrelation("P", $nombreDePhotos, "A", $nombreAmis, "Nombre de photo publiées (P)", "Nombre d'amis");
               break;
            case 5:
               TwoVarCorrelationPageGenerator::displayCorrelation("P", $nombreDePhotos, "J", $nombreMentionJaime, "Nombre de photo publiées (P)", "Nombre de mention j'aime (J)");
               break;
            default :
               break;
       }
    }
}

function displayCorrelationNombreDeLikeNombrePhoto() {
    $nombrePhotos = getNombrePhoto();
    $nombreLikes = getNombreDeLike();
    TwoVarCorrelationPageGenerator::displayCorrelation("P", $nombrePhotos, "L", $nombreLikes, "Nombre de photo publiées (P)", "Nombre de like (L)");
}

function displayCorrelationNombreDeLikeNombreAmis() {
    $nombreAmis = getNombreDamisOfFullyScannedUser();
    $nombreLikes = getNombreDeLikeOfFullyScannedUser();
    TwoVarCorrelationPageGenerator::displayCorrelation("A", $nombreAmis, "L", $nombreLikes, "Nombre d'amis (A)", "Nombre de like (L)");
}

function displayCorrelationNombreDePhotosNombreAmis() {
    $nombreAmis = getNombreDamisOfFullyScannedUser();
    $nombrePhotos = getNombreDeLikeOfFullyScannedUser();
    TwoVarCorrelationPageGenerator::displayCorrelation("A", $nombreAmis, "P",  $nombrePhotos, "Nombre d'amis (A)", "Nombre de photo publiées (P)");
}


/*
 * Le nombre de photo publiées pour chaque id.
 */
function getNombrePhoto() {
    $pdo = $GLOBALS['db'];
    $req = "
    SELECT postby as poster, count(*) as n
    FROM Photo
    WHERE postby IN ( SELECT pers FROM Phlike )
    GROUP BY postby
    ORDER BY postby
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}

function getNombreDeLike() {
    $pdo = $GLOBALS['db'];
    $req = " 
    SELECT pers, count(*) as n
    FROM Phlike
    WHERE pers IN (SELECT postby FROM Photo)
    GROUP BY pers
    ORDER BY pers
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}

function getNombrePhotoWithZeroAccounted() {
    $pdo = $GLOBALS['db'];
    $req = "
    SELECT Pers.id AS poster, count(Photo.postby) AS n
    FROM Pers LEFT OUTER JOIN Photo ON Pers.id = Photo.postby
    GROUP BY Pers.id
    ORDER BY Pers.id
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}

function getNombreDeLikeWithZeroAccounted() {
    $pdo = $GLOBALS['db'];
    $req = " 
    SELECT Pers.id AS liker, count(Phlike.pers) AS n
    FROM Pers LEFT OUTER JOIN Phlike ON Pers.id = Phlike.pers
    GROUP BY Pers.id
    ORDER BY Pers.id
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}

function getNombreDamisOfFullyScannedUser() {
    $pdo = $GLOBALS['db'];
    $req = " 
    SELECT p1.idstr as nom, count(DISTINCT Friend.idfrd) as \"Number of friends\"
    FROM Pers p1, Friend
    Where p1.id = Friend.idpers
    Group By p1.id
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}

function getNombrePhotoOfFullyScannedUser() {
    $pdo = $GLOBALS['db'];
    $req = "
    SELECT postby as poster, count(*) as n
    FROM Photo
    WHERE postby IN ( SELECT Friend.idpers FROM Friend )
    GROUP BY postby
    ORDER BY postby
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}

function getNombreDeLikeOfFullyScannedUser() {
    $pdo = $GLOBALS['db'];
    $req = " 
    SELECT Pers.id, count(*) as n
    FROM Pers LEFT OUTER JOIN Phlike ON Pers.id = Phlike.pers
    WHERE Pers.id IN (SELECT Friend.idpers FROM Friend )
    GROUP BY Pers.id
    ORDER BY Pers.id
    ";
    return $pdo->query($req, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_COLUMN, 1);
}
