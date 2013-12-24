<?php

require_once 'constant.definition.php';

class DBUtil {
	private $conn = null;
	
	private function DBUtil() {}
	
	public function getInstance() {
		if ($this->conn === null) {
			$this->conn = new PDO(dns, username, $password);
		}
	}
}