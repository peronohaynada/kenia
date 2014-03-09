<?php

/**
 * Remmember to comment every test that is not desired to be executed.
 * Also add always fail or pass messages.
 * Do not avoid the execution order if dependency requires!
 */
// set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/share/php5');
require_once 'errors/errors.control.php';
$start = time();
?>
<html>
	<head>
	<meta charset="utf-8">
		<style type="text/css">
			div > div {
				float:left;
				padding:0 10px 0 0;
				min-width:100px;
			}
		</style>
	</head>
	<body>
		<form method="post" action="?test">
			<label for="login">Login test</label><input type="checkbox" id="login" name="login" value="login" <?php echo ((isset($_POST ['login'])) ? "checked" : "" ) ?>><br>
			<label for="!login">Negative Login test</label><input type="checkbox" id="!login" name="!login" value="!login" <?php echo ((isset($_POST ['!login'])) ? "checked" : "" ) ?>><br>
			<label for="load">Load test</label><input type="checkbox" id="load" name="load" value="load" <?php echo ((isset($_POST ['load'])) ? "checked" : "" ) ?>><br>
			<label for="update">Update test</label><input type="checkbox" id="update" name="update" value="update" <?php echo ((isset($_POST ['update'])) ? "checked" : "" ) ?>><br>
			<label for="register">Register test</label><input type="checkbox" id="register" name="register" value="register" <?php echo ((isset($_POST ['register'])) ? "checked" : "" ) ?>><br>
			<label for="uploader">Uploader test</label><input type="checkbox" id="uploader" name="uploader" value="uploader" <?php echo ((isset($_POST ['uploader'])) ? "checked" : "" ) ?>><br>
			<input type="submit" value="execute">
		</form>
<?php
if (isset($_GET ['test'])) {
	if (isset($_POST ['login'])) {
		Logger::logWarning("Starting the Login Test execution");
		include_once 'unitTest/login.test.php';
		Logger::logWarning("Finish of Login Test execution");
	}
	if (isset($_POST ['!login'])) {
		Logger::logWarning("Starting the Negative Login Test execution");
		include_once 'unitTest/negative.login.test.php';
		Logger::logWarning("Finish of Negative Login Test execution");
	}
	
	if (isset($_POST ['load'])) {
		Logger::logWarning("Starting with Data Load test");
		include_once 'unitTest/load.data.test.php';
		Logger::logWarning("Finish of Data Load Test execution");
	}
	
	if (isset($_POST ['update'])) {
		Logger::logWarning("starting update test");
		include_once 'unitTest/update.test.php';
		Logger::logWarning("Finish of Login Test execution");
	}
	
	if (isset($_POST ['register'])) {
		Logger::logWarning("starting Register test");
		include_once 'unitTest/register.test.php';
		Logger::logWarning("Finish of Register Test execution");
	}
	
	if (isset($_POST ['uploader']) || isset($_GET['upload'])) {
		Logger::logWarning("starting Uploader test");
		include_once 'unitTest/uploader.test.php';
		Logger::logWarning("Finish of Uploader Test execution");
	}
}
//require_once 'templates/wrapper.php';
$end = time();
echo "<br>".($end - $start);
?>
	</body>
</html>