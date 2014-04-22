<?php
require_once 'errors/login.exception.php';
require_once 'objects/user/usuario.php';
require_once 'util/input.validator.util.php';
require_once 'util/constant.definition.php';
require_once 'templates/nco_files/ncoHelper.class.php';

session_start();
$sess = isset($_SESSION) && isset($_SESSION ['id']);
$user = new Usuario();

if (!$sess) {
	if (isset($_GET ['login'])) {
		$isLogin = true;
		try {
			if (InputValidatorUtil::loginValidator($_POST['username'], $_POST['password'])) {
				$user->login($_POST ['username'], $_POST ['password']);
				$_SESSION['id'] = $user->getUsuarioId();
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['tjuser'] = $user;
				$sess = true;
			}
		}
		catch (LoginException $le) {
			$isLogin = false;
			$loginError = $le->getMessage();
		}
	}
}
else {
	$user = $_SESSION['tjuser'];
	if (isset($_GET['update'])) {
		require_once 'templates/update.php';
	}
}

$index = new NCOHelper(Constants::$nco_template_path . Constants::$nco_skeleton_template);
$links;
if ($sess) {
	$links = '<li><a href="?update"><span>update</span></a></li>';
	$links .= '<li><a href="?settings"><span>settings</span></a></li>';
	$links .= '<li><a href="logout.php"><span>logout</span></a></li>';
}
else {
	$links = '<li><a href="?login"><span>login</span></a></li>';
	$links .= '<li><a href="?register"><span>register</span></a></li>';
}
$index->addContentToBuffer(Constants::$li_buttons, $links);

if ($sess) {

	if (isset($_GET['settings'])) {
		
	}
	else {
		$user->setUsuarioId($_SESSION['id']);
		$user->loadSokkerData();
		$juniors = $user->getSokkerData()->getJuniors();

		$i = 1;
		foreach ($juniors as $junior) {
			$junior->loadProgress();
			$class = ($i++ % 2 == 0) ? 'dark' : 'white';
			$info .= "<tr class='$class' id='{$junior->getId()}'>$junior</tr>\n";
		}
		$table = new NCOHelper(Constants::$nco_template_path . Constants::$nco_general_information_table_template);
		$table->addContentToBuffer(Constants::$junior_data, $info);
	}
	$index->addContentToBuffer(Constants::$main_content, $table->getBuffer());
}
else {
	if (!$isLogin) {
		$ncoLogin = new NCOHelper(Constants::$nco_template_path . Constants::$nco_login_template);
		$err = (isset($loginError)) ? "<div id='error'><span>$loginError</span></div>" : "";
		$ncoLogin->addContentToBuffer(Constants::$login_error, $err);
		$index->addContentToBuffer(Constants::$main_content, $ncoLogin->getBuffer());
	}
}
echo $index->getBuffer();
