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
			echo "CONNECTION EXCEPTION------" . $e->getMessage() . "<br>";
		}
	}

	public static function getInstance() {
		if (! isset(self::$dbUtil)) {
			self::$dbUtil = new DBUtil();
		}
	}

	public static function select($query, $params, $mode = null) {
		try {
			self::getInstance();
			$stmt = self::$dbUtil->conn->prepare($query);

			$stmt->execute($params);
			
			if (isset($mode)) {
				$result = $stmt->fetchAll($mode);
			}
			else {
				$result = $stmt->fetchColumn();
			}
			return $result;
		}
		catch (PDOException $e) {
			throw new PDOException("Problems with database, please try again later.");
		}
	}

	public static function insert($query, $params) {
	try {
			self::getInstance();
			self::$dbUtil->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$dbUtil->conn->beginTransaction();
			$stmt = self::$dbUtil->conn->prepare($query);
			
			$stmt->execute($params);
			$lastId = self::$dbUtil->conn->lastInsertId();
			self::$dbUtil->conn->commit();
			return $lastId;
		}
		catch (PDOException $e) {
			self::$dbUtil->conn->rollBack();
			throw new PDOException("Problems with database, please try again later.");
		}
	}

	public static function update($query, $params) {
	try {
			self::getInstance();
			$stmt = self::$dbUtil->conn->prepare($query);
			
			self::$dbUtil->conn->beginTransaction();
			$stmt->execute($params);
			self::$dbUtil->conn->commit();
		}
		catch (PDOException $e) {
			self::$dbUtil->conn->rollBack();
			throw new PDOException("Problems with database, please try again later.");
		}
	}
	
	public static function getConnection() {
		self::getInstance();
		return self::$dbUtil->conn;
	}
	
	public static function setJuniorsNotInSchool($sokkerTeamId) {
		$query = "update juniors set sigue_en_escuela = 0 where sokker_team_id=:sokker_team_id";
		$params[":sokker_team_id"] = $sokkerTeamId;
		self::insert($query, $params);
	}
	
	public static function deleteJuniorsNotInSchool($sokkerTeamId) {
		$query = "delete from juniors where sokker_team_id=:sokker_team_id and sigue_en_escuela=0";
		$params[":sokker_team_id"] = $sokkerTeamId;
		self::update($query, $params);
	}
}