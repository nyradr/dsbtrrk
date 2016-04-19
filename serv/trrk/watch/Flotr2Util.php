<?php


class Flotr2Util
{
    function getArraysAsCoordinateStringForFlotr2($array1, $array2){
       $res = "[";

        for ($i=0 ; $i< count($array1) ; $i++) {

            if (is_string($array2[$i])) {
                $res .= '['.$array1[$i].', "' .$array2[$i].'"]';
            }
            else {
                $res .= "[".$array1[$i].", ".$array2[$i]."]";
            }


            if ($i != (count($array1) - 1)) {
                $res .= ",";
            }
        }
        $res .= "]";
        return $res;
    }
}