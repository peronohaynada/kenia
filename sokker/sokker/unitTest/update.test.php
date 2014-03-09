<?php
require_once 'objects/user/usuario.php';
require_once 'util/db.util.php';

$username = "mathias";
$password = "mathias";

$user = new Usuario();
try {
	$user->login($username, $password);
	$sokkerTeamId = $user->getSokkerData()->getId($user->getUsuarioId());
	
	DBUtil::setJuniorsNotInSchool($sokkerTeamId);
	
	$user->updateDataFromSokker();
	
	DBUtil::deleteJuniorsNotInSchool($sokkerTeamId);
}
catch (PDOException $pdoe) {
	echo $pdoe->getMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}