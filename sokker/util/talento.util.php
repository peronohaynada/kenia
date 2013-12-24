<?php

//$habilidades = array(7,3,6,3,5,5,6,4,6,6,6,6,6,7,5,8,7,7,7);
//echo TalentoUtil::talentoJuvenil($habilidades);

class TalentoUtil {

	public static function talentoJuvenil ($habilidades) {
		$total = 0;
		$totalKey = 0;
		foreach ($habilidades as $key => $value) {
			$total += $value;
			//$totalKey += $key; // esto podr�a hacerse de la forma :: n*(n+1)/2
		}
		$size = count($habilidades);
		$totalKey = $size * ( $size - 1) / 2;
		$promedio = $total / $size;
		$promedioKey = $totalKey / $size;
		$primera = 0;
		$segunda = 0;
		foreach ($habilidades as $key => $value) {
			$tmpValue = $value - $promedio;
			$tmpKey = $key - $promedioKey;
			$primera += $tmpValue * $tmpKey;
			$segunda += pow($tmpKey, 2);
		}
		$resultado = ($primera / $segunda);
		return ($resultado == 0) ? 0 : 1 / $resultado;
	}
}