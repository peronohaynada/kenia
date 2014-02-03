<?php
// create table if not exists countries (
// country_id smallint(100) not null primary key,
// name varchar(256) not null,
// currency_name varchar(256) not null,
// currency_rate varchar(256) not null
// )
require_once 'xml.interface.php';
require_once 'util/db.util.php';

class CountryXmlImpl implements XmlI {
	
	private $countryID;
	private $nombre;
	private $currencyName;
	private $currencyRate;
	
	private $nodeName = "country";
	private $query = "INSERT INTO test.countries (country_id, nombre, currency_name, currency_rate) VALUES (:country_id, :nombre, :currency_name, :currency_rate)";
	
	public function loadFromXML(SimpleXMLElement $node, $nothing) {
		$this->countryID = $node->countryID;
		$this->nombre = $node->name;
		$this->currencyName = $node->currencyName;
		$this->currencyRate = $node->currencyRate;
	}

	public function insertIntoDB() {
		try {
			$db = DBUtil::getConexion();
			$stmt = $db->prepare($this->query);
	
			$stmt->bindParam(":country_id", $this->countryID);
			$stmt->bindParam(":nombre", $this->nombre);
			$stmt->bindParam(":currency_name", $this->currencyName);
			$stmt->bindParam(":currency_rate", $this->currencyRate);
			
			$stmt->execute();
		}
		catch (Exception $e) {
			throw new Exception("A country must be at war.. c41");
		}
	}
	
	public function getNodeName() {
		return $this->nodeName;
	}
}