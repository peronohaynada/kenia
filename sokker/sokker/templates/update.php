<?php
require_once 'objects/user/usuario.php';
require_once 'util/db.util.php';
require_once 'templates/nco_files/ncoHelper.class.php';

$updateError = '';
try {
	$user->getSokkerCredentials();
	if ($user->getConfirmacionCredenciales() == 1
			|| (isset($_GET['up']) && isset($_POST['spassword'])
				&& $_POST['spassword'] != null && $_POST['spassword'] != "")
				&& isset($_POST['susername']) && $_POST['susername'] != null
				&& $_POST['susername'] != "") {
		$sokkerTeamId = $user->getSokkerData()->getId($user->getUsuarioId());
		
		$uSokker = (isset($_POST['susername'])) ? $_POST['susername'] : null;
		$pSokker = (isset($_POST['spassword'])) ? $_POST['spassword'] : null;
		$user->loginToSokker($uSokker, $pSokker);
		
		// Only after successfully logged in sokker will store new credentials
		if ($_POST['confirmation'] == 'yes') {
			$user->setConfirmacionCredenciales(1);
			$user->getSokkerData()->updatePSokker($_POST['spassword']);
		}
	
		DBUtil::deleteJuniorsNotInSchool($sokkerTeamId);
	
		DBUtil::setJuniorsNotInSchool($sokkerTeamId);
	
		$user->updateDataFromSokker();
		$successfulyUpdated = true;
	}
}
catch (PDOException $pdoe) {
	$updateError = $pdoe->getMessage();
}
catch (Exception $e) {
	$updateError = $e->getMessage();
}