<?php

require_once 'constant.definition.php';

class DBUtil {
	private $conn;
	private static $dbUtil;
	
	
	private function __construct() {
		try {
		$this->conn = new PDO(dns, username, password);
		}
		catch (PDOException $e) {
			echo "CONNECTION EXCEPTION------".$e->getMessage()."<br>";
		}
	}
	
	public static function getConexion() {
		if (!isset(self::$dbUtil)) {
			self::$dbUtil = new DBUtil();
		}
		return self::$dbUtil->conn;
	}
}