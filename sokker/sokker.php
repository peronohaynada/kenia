<?php

include_once 'util/context.class.php';
require_once 'xml.objects/country.xml.php';
require_once 'util/xml.reader.util.php';

$context = new Context();
$context->setData("mattmad", "mamis22po");

$xmlFileNames = new XMLFileNames();
$xmlFileNames->setTeamID($context->sendLoginRequestToSokker());
$xmlNames = $xmlFileNames->getNombresXmlIndependientes();

foreach ($xmlNames as $xml) {
	$tmp = $context->downloadXML($xml);

	$fopen = fopen("xml/".$xml, "w");
	$fErrors = fwrite($fopen, $tmp);
	if ($xml == "countries.xml") {
		$countries = new CountryXmlImpl();
		XMLReaderUtil::readXML("xml/countries.xml", $countries, $countries->getNodeName());
	}
	fclose($fopen);

	if ($fErrors >= 1) {
		echo "success downloading: $xml<br>";
	}
	else {
		"errors with: $xml<br>";
	}
}