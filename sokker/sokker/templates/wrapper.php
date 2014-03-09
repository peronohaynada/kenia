<?php
require_once 'errors/login.exception.php';
require_once 'objects/user/usuario.php';
require_once 'util/input.validator.util.php';

session_start();
$user = new Usuario();

if (isset($_SESSION) && isset($_SESSION ['id'])) {
}
else {
	try {
		if (isset($_GET ['login'])) {
			if (InputValidatorUtil::loginValidator($_POST['username'], $_POST['password'])) {
				$user->login($_POST ['username'], $_POST ['password']);
			}
		}
	}
	catch (LoginException $le) {
		$loginError = $le->getMessage();
	}
}

if (isset($_SESSION) && isset($_SESSION ['id'])) {
}
else {

	require_once 'login.html';
}