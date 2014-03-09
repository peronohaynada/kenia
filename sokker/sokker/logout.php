<?php
session_start();
session_destroy();
session_unset();
$_SESSION = array();
?>
<html>
	<head>
		<title>economy</title>
		<!-- <link rel="shortcut icon" href="http://economy.mbarreneche.com/images/favicon.ico">-->
		<meta http-equiv="Refresh" content="2;url=http://localhost/sokker/index.php" />
	</head>
	<body>
		<?php echo "Goodbye!"; ?>
	</body>
</html>