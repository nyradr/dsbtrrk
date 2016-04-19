<?php

class MathMLMarkupGenerator {

    static function getCovarianceFormulaDeclaration($serieName1, $serieName2) {
        $cov =
        <<<MATHML
<mrow>
    <mi>COV</mi>
    <mfenced>
        <mi>$serieName1</mi>
        <mi>$serieName2</mi>
    </mfenced>
</mrow>
MATHML;

        return $cov;
    }

    private static function getCovarianceGeneralFormulaWithoutClosingFirstMrowTag($serieName1, $serieName2) {
        $covFormula = "<mrow> \n".MathMLMarkupGenerator::getCovarianceFormulaDeclaration($serieName1, $serieName2)."\n";

        $covFormula = $covFormula.
            <<<MATHML
    <mrow>
        <mo>=</mo>
        <menclose notation="top">
            <mrow>
                <mi>$serieName1</mi>
                <mo>&#x000D7;</mo>
                <mi>$serieName2</mi>
            </mrow>    
        </menclose>
        <mo>-</mo>
        <menclose notation="top">
            <mi>$serieName1</mi>
        </menclose> 
        <mo>&#x000D7;</mo>
        <menclose notation="top">
             <mi>$serieName2</mi>
        </menclose> 
    </mrow>
MATHML;

        return $covFormula;
    }

     static function getCovarianceGeneralFormula($serieName1, $serieName2) {

        $covFormula = MathMLMarkupGenerator::getCovarianceGeneralFormulaWithoutClosingFirstMrowTag($serieName1,$serieName2)."</mrow>";

        return $covFormula;
    }

    private static function getCovarianceWithValues($moyenneProduit, $moyenneSerie1, $moyenneSerie2, $resultat) {
        $covWithValue = <<<MATHML
<mrow>
    <mo>=</mo>
    <mn> $moyenneProduit </mn> 
	<mo>-</mo>
	<mn>$moyenneSerie1</mn>
	<mo>&#x000D7;</mo>
	<mn>$moyenneSerie2</mn>
	<mo>=</mo>
	<mn>$resultat</mn>	
</mrow>
MATHML;
        return $covWithValue;
    }

    static function getCovarianceFullLine($nomSerie1, $nomSerie2, $moyenneProduit, $moyenneSerie1, $moyenneSerie2, $resultat){
        $covFullLine = MathMLMarkupGenerator::getCovarianceGeneralFormulaWithoutClosingFirstMrowTag($nomSerie1, $nomSerie2).
            MathMLMarkupGenerator::getCovarianceWithValues($moyenneProduit, $moyenneSerie1, $moyenneSerie2, $resultat)."</mrow>"
        ;

        return $covFullLine;
    }

    private static function getAverageDeclaration($serieName) {
        $avg = <<<MATHML
<menclose notation="top">
   <mi>$serieName</mi>
</menclose>";
MATHML;

        $avg = '<menclose notation="top"><mi>'.$serieName.'</mi></menclose>';
        return $avg;
    }

    static function getAverageGeneralFormula($serieName) {
        $serieNameLowerCase = strtolower($serieName);
        $avg = MathMLMarkupGenerator::getAverageDeclaration($serieName);
        $avg .=  <<<MATHML
<mrow>        
    <mo>&#x000A0;</mo>
    <mo>=</mo>
    <mfrac>
        <mn>1</mn>
        <mi>n</mi>
    </mfrac>
    <mo>&#x000D7;</mo>
    <munderover>
        <mo>&#x02211;</mo>
        <mrow>
            <mi>$serieName</mi>
            <mo>=</mo>
            <mn>0</mn>
        </mrow>
        <mi>n</mi>
    </munderover>
    <msub>
        <mi>$serieNameLowerCase</mi>
        <mi>i</mi>
    </msub> 
</mrow>    
MATHML;

        return $avg;
    }


    static function getAverageFullLine($serieName, $n, $sum, $result) {
        $avg = "<mrow>".MathMLMarkupGenerator::getAverageGeneralFormula($serieName);
        $avg.= <<<MATHML
<mrow>        
    <mo>=</mo>
    <mfrac>
        <mn>1</mn>
        <mi>$n</mi>
    </mfrac>
     <mo>&#x000D7;</mo>
     <mn>$sum</mn>
     <mo>=</mo>
     <mn>$result</mn>
</mrow>
MATHML;
        return $avg."</mrow>";
    }

    private static function getVarianceDeclaration($serieName) {
        $variance = <<<MATHML
<mrow>
    <mi>VAR</mi>
    <mfenced>
        <mi>$serieName</mi>
    </mfenced>
</mrow>
MATHML;
        return $variance;
    }

    static function getVarianceGeneralFormula($serieName) {
        $varianceFormula = MathMLMarkupGenerator::getVarianceDeclaration($serieName)."\n";

        $varianceFormula .= <<< MATHML
<mrow>
    <mo>=</mo>
    <menclose notation="top">
        <msup>
            <mi>$serieName</mi>
            <mn>2</mn>
        </msup>    
    </menclose>
    <mo>-</mo>
    <msup>
        <mfenced>
            <menclose notation="top">
                <mi>$serieName</mi>
            </menclose> 
        </mfenced>    
        <mi> 2 </mi>
    </msup>    
</mrow>
MATHML;

        return $varianceFormula;
    }

    private static function getStandardDeviationDeclaration($serieName) {
        $sdev =  <<<MATHML
<mrow> 	
    <mi>&#x03C3;</mi>
    <mfenced>
        <mi> $serieName </mi>
    </mfenced>
</mrow>
MATHML;
        return $sdev;

    }

    static function getStandardDeviationGeneralFormula($serieName) {
        $sdev = MathMLMarkupGenerator::getStandardDeviationDeclaration($serieName).
            <<<MATHML
<mrow>
    <mo> = </mo>
    <msqrt>
        <mrow>
            <mi>VAR</mi>
            <mfenced>
                <mi>$serieName</mi>
            </mfenced>
        </mrow>    
    </msqrt>   
</mrow>
MATHML;
        return $sdev;
    }

    static function getStandardDeviationFullLine($serieName, $variance, $resultat) {
        $sdev = MathMLMarkupGenerator::getStandardDeviationGeneralFormula($serieName).
            <<<MATHML
<mrow>
    <mo> = </mo>
    <msqrt>
        <mn>$variance</mn>
    </msqrt>  
    <mo> = </mo>
    <mn>$resultat</mn>
</mrow>
MATHML;

        return $sdev;
}

    private static function getCoeffFormulaDeclaration() {
        $coeff =
            <<<MATHML
<mrow>
    <mrow>
        <mi>R</mi>
        <mo>=</mo>
    </mrow>
    <mrow>
        <mfrac>
MATHML;

        return $coeff;
    }

    static function getCoeffGeneralFormula($serieName1, $serieName2) {
        
        $coeff =  MathMLMarkupGenerator::getCoeffGeneralFormulaWithoutMrowTag($serieName1, $serieName2)
            ."</mrow>";

            return $coeff;
    }

    private static function getCoeffGeneralFormulaWithoutMrowTag($serieName1, $serieName2) {
        $coeff =  MathMLMarkupGenerator::getCoeffFormulaDeclaration()
            .MathMLMarkupGenerator::getCovarianceFormulaDeclaration($serieName1, $serieName2)
            ."<mrow>"
            .MathMLMarkupGenerator::getStandardDeviationDeclaration($serieName1)
            . "<mo>&#x000D7;</mo>"
            .MathMLMarkupGenerator::getStandardDeviationDeclaration($serieName2)
            ."</mrow> </mfrac>  </mrow>";

        return $coeff;
    }

    static function getCoeffFullLine($serieName1, $serieName2, $covariance, $sdevSerie1, $sdevSerie2, $resultat) {
        $coeff = MathMLMarkupGenerator::getCoeffGeneralFormulaWithoutMrowTag( $serieName1, $serieName2).
        <<<MATHML
<mrow>
    <mo>=</mo>
    <mfrac>
        <mrow>
            <mn> $covariance </mn>
        </mrow>
        <mrow>
            <mn>$sdevSerie1</mn>
            <mo>&#x000D7;</mo>
            <mn>$sdevSerie2</mn>
        </mrow>
    </mfrac> 
    <mo>=</mo>
    <mn> $resultat </mn>
</mrow> </mrow>
MATHML;
        return $coeff;
    }


}