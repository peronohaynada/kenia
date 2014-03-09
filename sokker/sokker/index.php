<?php
require_once 'errors/login.exception.php';
require_once 'objects/user/usuario.php';
require_once 'util/input.validator.util.php';

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
				$sess = true;
			}
		}
		catch (LoginException $le) {
			$isLogin = false;
			$loginError = $le->getMessage();
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Juves Talent</title>
<link rel="stylesheet" type="text/css" href="css/wrapper.css">
</head>
<body>
	<div id="body">
		<div class="logo">la</div>
		<div class="header">
<?php if ($sess): ?>
	<a href="#"><span><?php echo $_SESSION['username']; ?></span></a>
	<a href="logout.php"><span>logout</span></a>
<?php else: ?>
	<a href="?login"><span>login</span></a>
	<a href="?register"><span>register</span></a>
<?php endif;?>
		</div>
		<div class="navigator">la</div>
		<div class="content">
<?php

if ($sess) {
	$user->setUsuarioId($_SESSION['id']);
	$user->loadSokkerData();
	$juniors = $user->getSokkerData()->getJuniors();
	echo "<div class='div-table'>\n";
	foreach ($juniors as $junior) {
		$junior->loadProgress();
		echo "<div class='div-table-row' id='{$junior->getId()}'>$junior</div>\n";
	}
	echo "</div>\n";
}
else {
	if (!$isLogin) {
		require_once 'templates/login.html';
	}
}
?>
		</div>
	</div>
</body>
</html>