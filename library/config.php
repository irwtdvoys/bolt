<?php
	// General
	define("DEPLOYMENT", "development"); // development/testing/production
	define("API_NAME", "BOLT_DEV");

	// Versioning
	define("VERSION_INTERNAL_BOLT", "v0.0.0");
	define("VERSION_INTERNAL_API", "bolt");

	if (DEPLOYMENT == "development") // Framework expects server to be setup with no errors displayed
	{
		ini_set("display_errors", 1);
		ini_set("error_reporting", E_ALL & ~E_NOTICE);
	}

	require_once(ROOT_SERVER . "library/functions.php");
	require_once(ROOT_SERVER . "library/classes/Handler.php");

	$config = array("database", "roots");

	foreach ($config as $next)
	{
		require_once(ROOT_SERVER . "library/config/" . $next . ".php");
	}

	$connection = array(
		"type" => DB_TYPE,
		"host" => DB_HOST,
		"port" => DB_PORT,
		"database" => DB_NAME,
		"username" => DB_USER,
		"password" => DB_PASS,
		"autoconnect" => true
	);

	$_USERID = 0;
?>
