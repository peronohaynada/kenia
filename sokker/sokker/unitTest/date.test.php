<?php

require_once '../util/date.util.php';

if ("Thursday" == DateUtil::getDayFromDate(strtotime("2014-01-23"))) {
	echo "its correct!<br>";
}
echo date(l, time())."<br>";
echo ((DateUtil::determineIfUpdateNeeded()) ? "es jueves" : "no es jueves, es: ".DateUtil::getDayFromDate(time()));