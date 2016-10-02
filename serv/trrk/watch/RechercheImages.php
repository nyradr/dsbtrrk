<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='css/head.css'>
    <link rel='stylesheet' href='css/photosEtCmp.css'>
    <title>Title</title>
</head>
<body>

<?php
include_once "utils.php";
include_once "draw.php";
?>

<?php
drawHeader();

if (isset($_GET['id'])) {
    coPDO();
    $GLOBALS['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id = $_GET['id'];

    echo "<div id=\"listPhotos\"> <h1>Résultat de la recherche sur ".  getPersonIdstr($id) ."</h1>";
    $div .= displayPicturesPostedBy($id);
    $div .= displayPicturesLikedBy($id);
    $div .= "</div>";

    echo $div;
}
else {
    $div = <<<HTML
       
    <div>
        <form action="RechercheImages.php" method="get">
            Id de la personne dont on recherche du contenu:<br>
            <input type="text" name="id">
            <br>
            <input type="Submit" value="Rechercher">
        </form>
    </div>

HTML;
    echo $div;
}
?>
</body>
</html>

<?php

function displayPicturesPostedBy($personID){

    $resultsSet = getPicturesPostedBy($personID);
    echo '<p>';
    if ($resultsSet->rowCount() == 0) {
        echo 'Pas de photos publiée.';
    }
    else {
        echo "<table>";
        echo "<caption>Images postées par : ". getPersonIdstr($personID) ."</caption>";
        echo "<tr>";
        echo "<th>ID Photo</th>";
        echo "<th>URL</th>";
        echo "<th>Nombre de likes</th>";
        echo "</tr>";

        while ($result = $resultsSet->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".$result['id']."</td>";
            echo "<td>".$result['url']."</td>";
            echo "<td>".$result['n']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    echo '</p>';
}

function  displayPicturesLikedBy($personID){

    $resultsSet = getPicturesLikedBy($personID);
    echo '<p>';
    if ($resultsSet->rowCount() == 0) {
        echo 'Pas de like provenant de cette personne.';
    }
    else {
        echo "<table>";
        echo "<caption>Images likées par : ". getPersonIdstr($personID) ."</caption>";
        echo "<tr>";
        echo "<th>ID Photo</th>";
        echo "<th>Publiée par</th>";
        echo "<th>URL</th>";
        echo "</tr>";

        while ($result = $resultsSet->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".$result['id']."</td>";
            echo "<td>".$result['pictureOwner']."</td>";
            echo "<td>".$result['url']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo '</p>';
}
function getPicturesPostedBy($personID){
    $req = 'SELECT Photo.id as id, Photo.img as url, count(Phlike.img) as n
    FROM Photo LEFT OUTER JOIN Phlike ON Phlike.img = Photo.id
    WHERE Photo.postby = :id
    GROUP BY Phlike.img
    ';
    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id', $personID);
    $statement->execute();

    return $statement;
}

function getPicturesLikedBy($personID){
    $req = 'SELECT Photo.id as id, Pers.idstr as pictureOwner, Photo.img as url
    FROM Photo, Phlike, Pers
    WHERE Phlike.pers = :id
    AND Pers.id = Photo.postby
    AND Phlike.img = Photo.id
    ';
    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id', $personID);
    $statement->execute();

    return $statement;
}

function getLikers($personID) {
    $req = 'SELECT Pers.idstr as nom, Photo.id as id, Photo.img as url
    FROM Photo, Phlike, Pers
    WHERE Photo.postby = :id
    AND Phlike.img = Photo.id
    AND Pers.id = Photo.id
    ';
    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id', $personID);
    $statement->execute();

    return $statement;
}
