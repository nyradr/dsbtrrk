<?php

class TwoVarCorrelationPageGenerator  {


    static function displayCorrelation($serie1name, $serie1, $serie2name, $serie2, $serie1FullName="", $serie2FullName="") {
        if ($serie1FullName == ""){
            $serie1FullName = $serie1name;
        }
        if ($serie2FullName == ""){
            $serie2FullName = $serie2name;
        }
        $nSerie1 = count($serie1);
        $nSerie2 = count($serie2);

        if ($nSerie1 != $nSerie2) {
            echo "Erreur : Les séries sont de tailles différentes : $serie1name -> $nSerie1 valeurs, $serie2name -> $nSerie2 valeurs.";
        }
        else {
            $moyenneProduit = StatisticTools::getAverage(StatisticTools::mutltiplyAll($serie1,$serie2));
            $moyenneSerie1 = StatisticTools::getAverage($serie1);
            $moyenneSerie2 = StatisticTools::getAverage($serie2);
            $varianceSerie1 = StatisticTools::getVariance($serie1);
            $varianceSerie2 = StatisticTools::getVariance($serie2);
            $sdev1 = StatisticTools::getStandardDeviation($serie1);
            $sdev2 = StatisticTools::getStandardDeviation($serie2);
            $covariance = StatisticTools::getCovariance($serie1, $serie2);
            $coeff = StatisticTools::getCorrelationCoefficient($serie1, $serie2);

            $sumSerie1 = array_sum($serie1);

            $sumSerie2 = array_sum($serie2);
            echo TwoVarCorrelationPageGenerator::createHTMLDivForCorrelation($serie1FullName, $serie2FullName, $serie1name, $serie2name, $moyenneProduit, $nSerie1, $sumSerie1, $moyenneSerie1, $nSerie2, $sumSerie2, $moyenneSerie2, $varianceSerie1, $varianceSerie2, $covariance, $coeff, $sdev1, $sdev2);
        }


    }

    static private function createHTMLDivForCorrelation($serie1FullName, $serie2FullName, $serie1name, $serie2name, $moyenneProduit, $nSerie1, $sumSerie1, $moyenneSerie1, $nSerie2, $sumSerie2, $moyenneSerie2, $varianceSerie1, $varianceSerie2, $covariance, $coeff, $sdev1, $sdev2) {
        $html = "<div>";
        $html .= "<h3> Analyse simple de la correlation entre " .$serie1FullName. " et " .$serie2FullName."</h3> \n";
        $html .= "<p>";
        $html .= "Moyennes : \n";
        $html .= '<math xmlns="http://www.w3.org/1998/Math/MathML" display="block">';
        $html .= MathMLMarkupGenerator::getAverageFullLine($serie1name, $nSerie1, $sumSerie1, $moyenneSerie1);
        $html .= '<mspace width="100px" />'; //Un espace
        $html .= MathMLMarkupGenerator::getAverageFullLine($serie2name, $nSerie2, $sumSerie2, $moyenneSerie2);
        $html .= "</math>";
        $html .= "\n Covariance : \n";
        $html .= '<math xmlns="http://www.w3.org/1998/Math/MathML" display="block">';
        $html .= MathMLMarkupGenerator::getCovarianceFullLine($serie1name, $serie2name, $moyenneProduit, $moyenneSerie1, $moyenneSerie2, $covariance);
        $html .= "</math>";
        $html .= "Ecart type $serie1name : \n";
        $html .= '<math xmlns="http://www.w3.org/1998/Math/MathML" display="block">';
        $html .= MathMLMarkupGenerator::getStandardDeviationFullLine($serie1name,$varianceSerie1, $sdev1);
        $html .= "</math>";
        $html .= "Ecart type $serie2name : \n";
        $html .= '<math xmlns="http://www.w3.org/1998/Math/MathML" display="block">';
        $html .= MathMLMarkupGenerator::getStandardDeviationFullLine($serie2name,$varianceSerie2, $sdev2);
        $html .= "</math>";
        $html .= "Coefficient de correlation: \n";
        $html .= '<math xmlns="http://www.w3.org/1998/Math/MathML" display="block">';
        $html .= MathMLMarkupGenerator::getCoeffFullLine($serie1name, $serie2name, $covariance, $sdev1,$sdev2,$coeff);
        $html .= "</math>";
        $html .= "</p>";
        if ($coeff >= 0.7) {
            $html .= "<p> Il semble y avoir corrélation entre ".$serie1name." et ".$serie2name."</p>";
        }
        else {
            $html .= "<p> Il semble ne pas y avoir corrélation entre " . $serie1name . " et " . $serie2name . "</p>";
        }
        $html .= "</div>";

        return $html;
    }
}



