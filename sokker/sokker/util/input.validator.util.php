<?php
require_once 'errors/login.exception.php';

class InputValidatorUtil {
	
	public static function loginValidator($username, $password) {
		if ($username == null || $username == "" || $password == null || $password == "") {
			throw new LoginException("Username and password are required.");
		}
		else {
			return true;
		}
	}
}