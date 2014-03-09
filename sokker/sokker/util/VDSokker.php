<?php

/**
 * VDSokker - Validacion Descarga Sokker
 */
require_once 'xml.objects/juniors.xml.php';
require_once 'xml.objects/country.xml.php';
require_once 'util/xml.reader.util.php';
require_once 'errors/errors.control.php';

class VDSokker {
	private $context;
	private static $instance;
	
	private function __construct() {
		if (!isset(self::$context)) {
			$this->context = new Context();
		}
	}
	
	public static function getContextInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new VDSokker();
		}
		return self::$instance->context;
	}
	
	public static function getInstance() {
		return self::$instance;
	}

	public static function loginToSokker($uSokker, $pSokker) {
		try {
			self::getContextInstance()->setData($uSokker, $pSokker);
			return self::getContextInstance()->sendLoginRequestToSokker();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public static function getContextError() {
		return self::getContextInstance()->isError();
	}

	public static function descargaXML($sokkerId, $dbId) {
		try {
			$xmlFileNames = new XMLFileNames();
			$xmlFileNames->setTeamID($sokkerId);
			$xmlNames = $xmlFileNames->getNombresXmlIndependientes();
			
			foreach ($xmlNames as $xml) {
				Logger::logWarning("Starting to process $xml");
				$tmp = self::getContextInstance()->downloadXML($xml);
				$fname = "xml/" . $sokkerId . "-" . $xml;
				$fopen = fopen($fname, "w");
				$fErrors = fwrite($fopen, $tmp);
				
				if ($fErrors >= 1) {
					$xmlCorrecto = self::getInstance()->getXMLCorrecto($xml);
					XMLReaderUtil::readXML($fname, $xmlCorrecto, $dbId);
					Logger::logWarning("success downloading: $xml");
				}
				else {
					Logger::logWarning("errors with: $xml");
				}
			}
		}
		catch (PDOException $pdoe) {
			throw new PDOException($pdoe->getMessage(), $pdoe->getCode(), $pdoe->getPrevious());
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		finally {
			fclose($fopen);
			unlink($fname);
		}
	}
	
	public static function getContextErrorMessage() {
		return self::getContextInstance()->getErrorMessage();
	}

	private function getXMLCorrecto($xml) {
		$xmlCorrecto = null;
		if ($xml == "countries.xml") {
			$xmlCorrecto = new CountryXmlImpl();
		}
		else if ($xml == "juniors.xml") {
			$xmlCorrecto = new JuniorXmlImpl();
		}
		return $xmlCorrecto;
	}
}