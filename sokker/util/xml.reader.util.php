<?php
require_once 'xml.objects/xml.interface.php';

class XMLReaderUtil {
	public static function readXML($xmlFileName, XmlI $xmlObject, $nodeName) {
		try {
			$xmlReader = new XMLReader();
			$doc = new DOMDocument();
			$xmlReader->open($xmlFileName);
			while($xmlReader->read() && $xmlReader->name !== $nodeName);
			while($xmlReader->name === $nodeName) {
				$node = new SimpleXMLElement($xmlReader->readOuterXML());
				$xmlObject->loadFromXML($node);
				$xmlObject->insertIntoDB();
				$xmlReader->next($nodeName);
			}
			$xmlReader->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}