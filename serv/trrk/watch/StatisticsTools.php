<?php
class StatisticTools {
	
	static function getVariance($listOfValues) {
		
		// VAR(X) = average ((Xi - average(Xi))^2)
		
		// $res = 0;
		$avg = self::getAverage($listOfValues);
		
		$avgOfSquare = self::getAverageOfSquare($listOfValues); // Carré de la Moyenne.
		$avgSquared = $avg * $avg; // Moyenne des valeurs au carré.
		
		/*
		 * foreach ($listOfValues as $val) {
		 * $res = $res + ($val - $avg)*($val - $avg);
		 * }
		 * $res += $res / count($listOfValues);
		 */
		
		return $avgOfSquare - $avgSquared;
	}
	
	static function getCovariance($listOfValues1, $listOfValues2) {
		// COV(X, Y) = average (Xi * Yi) - average(Xi) * average(Yi)
		$avgXY = self::getAverage(self::mutltiplyAll ($listOfValues1, $listOfValues2)); // Moyenne des produits
		
		$covXY = $avgXY - self::getAverage($listOfValues1) * self::getAverage ($listOfValues2);
		
		return $covXY;
	}
	
	static function getAverageOfSquare($listOfValues) {
	
		$sumOfSquared = 0;
		foreach ($listOfValues as $value) {
			$sumOfSquared = $sumOfSquared + $value*$value;
		}
		
		return $sumOfSquared / count ($listOfValues);
	}
	
	static function getAverage($listOfValues) {
		return array_sum($listOfValues) / count ($listOfValues);
	}
	
	static function mutltiplyAll($list1, $list2) {
		$result = array();
		$arraySize =  count($list1);
		for($i = 0; $i < $arraySize ; $i++) {
			$result[$i] = $list1[$i] * $list2[$i];
		}
		return $result;
	}
	
	/**
	 * Ecart-type
	 */
	static function getStandardDeviation($listOfValues) {
		return sqrt ( self::getVariance($listOfValues));
	}
	
	static function getCorrelationCoefficient($listOfValues1, $listOfValues2) {
		// Covariance divisée par le produit des écarts-types
		return self::getCovariance ( $listOfValues1, $listOfValues2 ) / (self::getStandardDeviation ( $listOfValues1 ) * self::getStandardDeviation( $listOfValues2 ));
	}
}

