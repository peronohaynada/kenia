<?php
require_once 'objects/user/usuario.php';
require_once 'util/db.util.php';

try {
	$user->getSokkerCredentials();
	$sokkerTeamId = $user->getSokkerData()->getId($user->getUsuarioId());

	$user->loginToSokker();

	DBUtil::deleteJuniorsNotInSchool($sokkerTeamId);

	DBUtil::setJuniorsNotInSchool($sokkerTeamId);

	$user->updateDataFromSokker();
}
catch (PDOException $pdoe) {
	echo $pdoe->getMessage();
}
catch (LoginException $le) {
	echo $le->getMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}