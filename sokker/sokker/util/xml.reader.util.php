<?php
require_once 'xml.objects/xml.interface.php';

class XMLReaderUtil {

	public static function readXML($xmlFileName, XmlI $xmlObject, $dbId) {

		try {
			$xmlReader = new XMLReader();
			$doc = new DOMDocument();
			$xmlReader->open($xmlFileName);
			$nodeName = $xmlObject->getNodeName();
			
			while ($xmlReader->read() && $xmlReader->name !== $nodeName)
				;
			while ($xmlReader->name === $nodeName) {
				$node = new SimpleXMLElement($xmlReader->readOuterXML());
				$xmlObject->loadFromXML($node, $dbId);
				$xmlObject->insertIntoDB($dbId);
				$xmlReader->next($nodeName);
			}
			$xmlReader->close();
		}
		catch (PDOException $pdoe) {
			throw new PDOException($pdoe->getMessage(), $pdoe->getCode(), $pdoe->getPrevious());
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}