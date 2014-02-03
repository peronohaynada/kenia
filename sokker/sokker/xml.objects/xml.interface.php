<?php

interface XmlI {
	public function loadFromXML(SimpleXMLElement $node, $sokkerTeamId);
	public function insertIntoDB();
	public function getNodeName();
}