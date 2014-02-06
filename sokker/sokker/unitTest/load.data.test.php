<?php
require_once 'objects/user/usuario.php';

$usuario = 'mathias';
$contrasena = 'mathias';
$usr = new Usuario();
try {
	$logueado = $usr->logueo($usuario, $contrasena);
	if ($logueado == 1) {
		$usr->loadSokkerData();
		$tmp = $usr->getSokkerData();
		$juniors = $tmp->getJuniors();
		foreach ($juniors as $junior) {
			var_dump($junior);
			echo "<br><br>";
		}
	}
}
catch (Exception $e) {
	echo $e->getMessage();
}