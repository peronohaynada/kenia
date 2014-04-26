<?php

/**
 * Constant definition file
 */

define("loginUrl", "http://online.sokker.org/start.php?session=xml");
define("xmlUrl", "http://online.sokker.org/xml/");

// File upload definitions
define("file_size", "1048576");

// Data base value definitions
define("dns", "mysql:dbname=test;host=127.0.0.1");
define("username", "root");
define("password", "root");

define("enckeycode", "paso.maksjg~snjiuv½69087b");

define("WARNING_LOG", "errors/warnings.log");//fix the path in case it doesn't log

class Constants {
	// common templates path
	public static $nco_template_path = "templates/nco_files/";
	
	// template name
	public static $nco_skeleton_template = "skeleton.nco";
	// place holders
	public static $li_buttons = "[li_buttons]";
	public static $main_content = "[main_content]";
	
	// template name
	public static $nco_login_template = "login.nco";
	// place holders
	public static $login_error = "[login_error]";
	
	// template name
	public static $nco_general_information_table_template = "general.information.table.nco";
	// place holders
	public static $junior_data = "[junior_data]";
	
	//template name
	public static $nco_register_template = "register.nco";
	//place holders
	public static $register_error = "[register_error]";
	
	//template name
	public static $nco_settings_template = "settings.nco";
	//place holders
	public static $settings_error = "[settings_error]";
	
	//template name
	public static $nco_update_template = "update.nco";
	//place holders
	public static $update_error = "[update_error]";
}