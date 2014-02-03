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
		var_dump($tmp);
	}
}
catch (Exception $e) {
	echo $e->getMessage();
}