<?php

//$hability = array(7,3,6,3,5,5,6,4,6,6,6,6,6,7,5,8,7,7,7);
//echo TalentUtil::juniorTalent($hability);

class TalentUtil {

	public static function juniorTalent ($hability) {
		$total = 0;
		$totalKey = 0;
		foreach ($hability as $key => $value) {
			$total += $value;
			//$totalKey += $key; // could be done in the following way :: n*(n+1)/2
		}
		$size = count($hability);
		if ($size == 1) {
			return 'N/A';
		}
		$totalKey = $size * ( $size + 1) / 2;
		$average = $total / $size;
		$averageKey = $totalKey / $size;
		$first = 0;
		$second = 0;
		foreach ($hability as $key => $value) {
			$tmpValue = $value - $average;
			$tmpKey = $key - $averageKey;
			$first += $tmpValue * $tmpKey;
			$second += pow($tmpKey, 2);
		}
		$result = ($first / $second);
		return ($first == 0) ? 0 : 1 / $result;
	}
}