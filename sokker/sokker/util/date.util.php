<?php

class DateUtil {
	public static function getDayFromDate($dateTime) {
		return date("l", $dateTime);
	}
	
	public static function determineIfUpdateNeeded() {
		return ("Thursday" != self::getDayFromDate(time()));
	}
}