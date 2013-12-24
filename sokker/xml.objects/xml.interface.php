<?php

interface XmlI {
	public function loadFromXML(SimpleXMLElement $node);
	public function insertIntoDB(/*PDO &$db*/);
	public function getNodeName();
}