<?php

/**
 * VDSokker - Validacion Descarga Sokker
 */
require_once 'xml.objects/juniors.xml.php';
require_once 'xml.objects/country.xml.php';
require_once 'util/xml.reader.util.php';

class VDSokker {
	private $context;

	public function validarCredenciales($uSokker, $pSokker) {

		try {
			$this->context = new Context();
			$this->context->setData($uSokker, $pSokker);
			return $this->context->sendLoginRequestToSokker();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function getContextError() {

		return $this->context->isError();
	}

	public function descargaXML($sokkerId, $dbId) {

		try {
			$xmlFileNames = new XMLFileNames();
			$xmlFileNames->setTeamID($sokkerId);
			$xmlNames = $xmlFileNames->getNombresXmlIndependientes();
			
			foreach ($xmlNames as $xml) {
				$tmp = $this->context->downloadXML($xml);
				$fname = "xml/" . $sokkerId . "-" . $xml;
				$fopen = fopen($fname, "w");
				$fErrors = fwrite($fopen, $tmp);
				
				if ($fErrors >= 1) {
					$xmlCorrecto = $this->getXMLCorrecto($xml);
					XMLReaderUtil::readXML($fname, $xmlCorrecto, $dbId);
					fclose($fopen);
					unlink($fname);
					echo "success downloading: $xml<br>";
				}
				else {
					"errors with: $xml<br>";
				}
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
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