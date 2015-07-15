<?php
	// General
	define("DEPLOYMENT", "production"); // development/testing/production
	define("API_NAME", "BOLT_LIVE");

	// Versioning
	define("VERSION_INTERNAL_BOLT", "v0.2.2");
	define("VERSION_INTERNAL_API", "v0.2");

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

	$_USERID = 0;
?>
