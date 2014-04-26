<?php
require_once 'errors/login.exception.php';
require_once 'objects/user/usuario.php';
require_once 'util/input.validator.util.php';
require_once 'util/constant.definition.php';
require_once 'templates/nco_files/ncoHelper.class.php';

session_start();
$sess = isset($_SESSION) && isset($_SESSION ['id']);
$user = new Usuario();
$successfulyUpdated = false;
$ncoSkeleton = new NCOHelper(Constants::$nco_template_path . Constants::$nco_skeleton_template);

if (!$sess) {
	$isLogin = true;
	$isRegister = false;
	if (isset($_GET ['login'])) {
		try {
			if (InputValidatorUtil::loginValidator($_POST['username'], $_POST['password'])) {
				$user->login($_POST ['username'], $_POST ['password']);
				$_SESSION['id'] = $user->getUsuarioId();
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['tjuser'] = $user;
				$sess = true;
			}
		}
		catch (Exception $le) {
			$isLogin = false;
			$loginError = $le->getMessage();
		}
	}
	else if (isset($_GET['register'])) {
		try {
			if (InputValidatorUtil::registerValidator($_POST['username'], $_POST['password'],
					$_POST['susername'], $_POST['spassword'])) {
				$confirmaCredenciales = ($_POST['confirmation'] == 'yes') ? 1 : 0;
				$user->registro($_POST['username'], $_POST['password'], $_POST['susername'],
						$_POST['spassword'], $confirmaCredenciales);
				$_SESSION['id'] = $user->getUsuarioId();
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['tjuser'] = $user;
				$isRegister = true;
			}
		}
		catch (Exception $le) {
			$isRegister = false;
			$registerError = $le->getMessage();
		}
	}
}
else {
	$user = $_SESSION['tjuser'];
	if (isset($_GET['update']) || isset($_GET['up'])) {
		require_once 'templates/update.php';

		if (!$successfulyUpdated) {
			$ncoUpdate = new NCOHelper(Constants::$nco_template_path . Constants::$nco_update_template);
			$ncoUpdate->addContentToBuffer(Constants::$update_error, $updateError);
			$ncoSkeleton->addContentToBuffer(Constants::$main_content, $ncoUpdate->getBuffer());
		}
	}
}

$links;

if ($sess) {
	$user->setUsuarioId($_SESSION['id']);

	$links = '<li><a href="?update"><span>update</span></a></li>';
	$links .= '<li><a href="?settings"><span>settings</span></a></li>';
	$links .= '<li><a href="logout.php"><span>logout</span></a></li>';

	if (isset($_GET['settings'])) {
		$ncoSettings = new NCOHelper(Constants::$nco_template_path . Constants::$nco_settings_template);
		$ncoSkeleton->addContentToBuffer(Constants::$main_content, $ncoSettings->getBuffer());
	}
	else if (!isset($_GET['update']) || $successfulyUpdated) {
		$user->loadSokkerData();
		$juniors = $user->getSokkerData()->getJuniors();

		$i = 1;
		foreach ($juniors as $junior) {
			$junior->loadProgress();
			$class = ($i++ % 2 == 0) ? 'dark' : 'white';
			$info .= "<tr class='$class' id='{$junior->getId()}'>$junior</tr>\n";
		}
		$ncoTable = new NCOHelper(Constants::$nco_template_path . Constants::$nco_general_information_table_template);
		$ncoTable->addContentToBuffer(Constants::$junior_data, $info);
		$ncoSkeleton->addContentToBuffer(Constants::$main_content, $ncoTable->getBuffer());
	}
	$_SESSION['tjuser'] = $user;
}
else {

	$links = '<li><a href="?login"><span>login</span></a></li>';
	$links .= '<li><a href="?register"><span>register</span></a></li>';

	if (!$isLogin) {
		$ncoLogin = new NCOHelper(Constants::$nco_template_path . Constants::$nco_login_template);
		$err = (isset($loginError)) ? "<div id='error'><span>$loginError</span></div>" : "";
		$ncoLogin->addContentToBuffer(Constants::$login_error, $err);
		$ncoSkeleton->addContentToBuffer(Constants::$main_content, $ncoLogin->getBuffer());
	}
	else if (isset($_GET['register']) && !$isRegister) {
		$ncoRegister = new NCOHelper(Constants::$nco_template_path . Constants::$nco_register_template);
		$err = (isset($registerError)) ? "<div id='error'><span>$registerError</span></div>" : "";
		$ncoRegister->addContentToBuffer(Constants::$register_error, $err);
		$ncoSkeleton->addContentToBuffer(Constants::$main_content, $ncoRegister->getBuffer());
	}
}
$ncoSkeleton->addContentToBuffer(Constants::$li_buttons, $links);
echo $ncoSkeleton->getBuffer();
