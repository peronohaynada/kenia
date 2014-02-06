<?php

/**
 * Remmember to comment every test that is not desired to be executed.
 * Also add always fail or pass messages.
 * Do not avoid the execution order if dependency requires!
 */
//set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/share/php5');
require_once 'errors/errors.control.php';

Logger::logWarning("Starting the Login Test execution");
include_once 'unitTest/login.test.php';

Logger::logWarning("Starting with Data Load test");
include_once 'unitTest/load.data.test.php';