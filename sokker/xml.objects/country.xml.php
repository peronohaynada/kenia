<?php

require_once 'xml.interface.php';

class CountryXmlImpl implements XmlI {
	
	private $countryID;
	private $name;
	private $currencyName;
	private $currencyRate;
	
	private $nodeName = "country";
	const query = "";

	public function loadFromXML(SimpleXMLElement $node) {
		$this->countryID = $node->countryID;
		$this->name = $node->name;
		$this->currencyName = $node->currencyName;
		$this->currencyRate = $node->currencyRate;
	}

	public function insertIntoDB(/*PDO &$db*/) {
		$stmt = $db->prepare(query);
		
		$stmt->setAttribute(1, $this->countryID);
		$stmt->setAttribute(2, $this->name);
		$stmt->setAttribute(3, $this->currencyName);
		$stmt->setAttribute(4, $this->currencyRate);
		
		return $stmt;
		
		//echo "inserting into data base ".$this->countryID.", ".$this->name.", ".$this->currencyName." and ".$this->currencyRate."<br>";
	}
	
	public function getNodeName() {
		return $this->nodeName;
	}
}