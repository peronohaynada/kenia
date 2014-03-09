<?php
require_once 'objects/user/usuario.php';
require_once 'errors/login.exception.php';

$usuario = 'mathias';
$contrasena = 'mathias';
$usr = new Usuario();
try {
	$usr->login($usuario, $contrasena);
	$usr->loadSokkerData();
	$juniors = $usr->getSokkerData()->getJuniors();
	foreach ($juniors as $junior) {
		$junior->loadProgress();
		echo "{$junior->__toString()}<br>";
	}
}
catch (LoginException $e) {
	echo $e->getMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}