<?php
require_once 'objects/user/usuario.php';
require_once 'errors/login.exception.php';

$usuario = 'mathias';
$contrasena = 'mathias';
$uSokker = '';
$pSokker = '';
$confirmaCredenciales = 1;

$usr = new Usuario();
try {
	$usr->login($usuario, $contrasena);
	echo $usr->getUsuarioId();
}
catch (LoginException $le) {
	echo $le->getMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}
