<?php

require_once 'xml.objects/country.xml.php';
require_once 'util/xml.reader.util.php';

$countries = new CountryXmlImpl();
XMLReaderUtil::readXML("xml/countries.xml", $countries, $countries->getNodeName());