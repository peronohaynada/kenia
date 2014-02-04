<?php

require_once 'objects/user/usuario.php';

$usuario = 'mathias';
$contrasena = 'mathias';
$uSokker = 'mattmad';
$pSokker = 'mamis22po';
$confirmaCredenciales = 1;

$usr = new Usuario();
try {
if ($usr->registro($usuario, $contrasena, $uSokker, $pSokker, $confirmaCredenciales)) {
	echo "usuario registrado<br>";
}
else {
	echo "usuario ya esta registrado!<br>";
}

$logueado = $usr->logueo($usuario, $contrasena);
echo (($logueado == 1) ? 'logueado' : 'error');
}
catch (Exception $e) {
	echo $e->getMessage();
}