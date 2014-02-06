<?php
require_once 'util/constant.definition.php';

class Logger {
	public static function logWarning($message) {
		$currentTime = "[".date("d-M-Y H:i:s", time())."] ";
		error_log($currentTime.$message."\n", 3, WARNING_LOG);
	}
}