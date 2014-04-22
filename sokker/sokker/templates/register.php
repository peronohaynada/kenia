<?php
require_once 'objects/user/usuario.php';
require_once 'errors/register.exception.php';

/*
$usuario = 'test';
$contrasena = 'test';
$uSokker = 'test';
$pSokker = 'test';
$confirmaCredenciales = 1;
*/
$usr = new Usuario();
try {
	$usr->registro($usuario, $contrasena, $uSokker, $pSokker, $confirmaCredenciales);
	echo $usr->getUsuarioId();
}
catch (RegisterException $le) {
	echo $le->getMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}