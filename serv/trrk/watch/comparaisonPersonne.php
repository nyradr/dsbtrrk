<?php
include_once "Flotr2Util.php";
include_once "utils.php";
include_once "draw.php";
connect();
coPDO();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
		<link rel='stylesheet' href='css/head.css'>
        <link rel='stylesheet' href='css/photosEtCmp.css'>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/prototype/1.7.2/prototype.js"></script>
        <script type="text/javascript" src="https://flotr.googlecode.com/svn-history/r319/branches/flotr2/flotr/flotr2/flotr2.min.js"></script>
        <script type="text/javascript">
            function basic_radar(container) {
                var
                    s1 = {
                        label: 'Activité de <?php echo getPersonIdstr($_GET['id1']) ?> ',
                        data:
                           <?php
                             if (isset($_GET['id1'])) {
                                 $array1id1 = getDataArrayForFlotr2($_GET['id1']);
                                 $array2 = getDataArrayForFlotr2($_GET['id1']);
                             }
                             else {
                                 $array1 = array(0, 0, 0);
                                 $array2 = array(0, 0, 0);
                             }
                                echo Flotr2Util::getArraysAsCoordinateStringForFlotr2($array1id1, $array2);
                           ?>

                    },
                    s2 = {
                        label: 'Activité de <?php echo getPersonIdstr($_GET['id2']) ?> ',
                        data:
                        <?php
                        if (isset($_GET['id2'])) {
                            $array1id2  = getDataArrayForFlotr2($_GET['id2']);
                            $array2 = getDataArrayForFlotr2($_GET['id2']);
                        }
                        else {
                            $array1 = array(0, 0, 0);
                            $array2 = array(0, 0, 0);
                        }
                        echo Flotr2Util::getArraysAsCoordinateStringForFlotr2($array1id2, $array2);
                        ?>

                    },
                    graph, ticks;

                // Radar Labels
                ticks = [
                    [0, "Nombre d'amis"],
                    [1, "Nombre de likes"],
                    [2, "Nombre de photos publiées"]
                ];

                // Draw the graph.
                graph = Flotr.draw(container, [s1, s2], {
                    radar: {
                        show: true
                    },
                    grid: {
                        circular: true,
                        minorHorizontalLines: true
                    },
                    yaxis: {
                        min: 0,
                        max: <?php echo max(array(max($array1id1), max($array1id2))); ?>,
                        minorTickFreq: 2
                    },
                    xaxis: {
                        ticks: ticks
                    }
                });
            };
            $('radar');

            function init() {
                basic_radar($('radar'));
            }

            Event.observe(window, 'load', init, false);
        </script>
        <title>Title</title>
    </head>
    <body>

<?php
        drawHeader();
        if (isset($_GET['id1']) && isset($_GET['id2']) ) {
            connect();
            coPDO();
            $GLOBALS['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $id1 = $_GET['id1'];
            $id2 = $_GET['id2'];

            if (!isPersonId($_GET['id1']) || !isPersonId($_GET['id2'])) {
                echo "id inconnu";
            }
            else {
                echo "<div> <h1>Comparaison entre ". getPersonIdstr($id1). " et " . getPersonIdstr($id2) ." </h1>";
                $div .=  "<div id='radar'> </div>";
                echo $div;

                displayCommonFriend($id1, $id2);
            }

        }
        else {
        $div = <<<HTML

        <div>
            <form action="comparaisonPersonne.php" method="get">
                Id de la première personne :<br>
                <input type="text" name="id1">
                <br>
                Id de la seconde personne :<br>
                <input type="text" name="id2">
                <br>
                <input type="submit" value="Comparer">
            </form>
        </div>

HTML;
        echo $div;
        }
?>
    </body>
</html>

<?php

function getDataArrayForFlotr2($personID) {
    return array(intval(getNombreDamis($personID)), intval(getNombreDeLike($personID)), intval(getNombreDePhoto($personID)));
}

function getNombreDamis($personID) {
    $req = "SELECT count(*) 
            FROM Friend
            WHERE Friend.idpers = :id
            OR Friend.idfrd = :id";

    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id', $personID);
    $statement->execute();

    return $statement->fetchColumn();

}
function getNombreDeLike($personID) {
    $req = "SELECT count(*) 
            FROM Phlike 
            WHERE Phlike.pers = :id";

    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id', $personID);
    $statement->execute();

    return $statement->fetchColumn();
}

function getNombreDePhoto($personID) {
    $req = "SELECT count(*) 
            FROM Photo 
            WHERE postby = :id";

    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id', $personID);
    $statement->execute();

    return $statement->fetchColumn();
}

function getCommonFriend($personID1, $personID2) {
    $req = "SELECT DISTINCT Pers.idstr
            FROM Friend, Pers
            WHERE Friend.idfrd <> 0
            AND (Friend.idpers = :id1
            OR Friend.idfrd = :id1)
            AND (Pers.id = Friend.idpers
            OR Pers.id = Friend.idfrd)
            AND Pers.id <> :id1
            AND Pers.idstr IN (
                SELECT DISTINCT Pers.idstr
                FROM Friend, Pers
                WHERE Friend.idfrd <> 0
                AND (Friend.idpers = :id2
                OR Friend.idfrd = :id2)
                AND (Pers.id = Friend.idpers
                OR Pers.id = Friend.idfrd)
                AND Pers.id <> :id2
            )
    ";

    $statement = $GLOBALS['db']->prepare($req);
    $statement->bindParam(':id1', $personID1);
    $statement->bindParam(':id2', $personID2);
    $statement->execute();

    return $statement;
}

function displayCommonFriend($personID1, $personID2) {
    echo "<div id=\"commonFriend\">";
    $resultsSet = getCommonFriend($personID1, $personID2);
    if ($resultsSet->rowCount() == 0) {
        echo 'Pas de like provenant de cette personne.';
    }
    else {
        echo "<table>";
        echo "<caption>Amis communs</caption>";
        echo "<tr>";
        echo "<th>Nom</th>";
        echo "</tr>";
        while ($result = $resultsSet->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".$result['idstr']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    echo "</div>";



}
