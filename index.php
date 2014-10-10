<?php
	define("ROOT_SERVER", __DIR__ . "/");

	require_once(ROOT_SERVER . "library/config.php");

	spl_autoload_register(array("Bolt\\Handler", "loader"));
	set_error_handler(array("Bolt\\Handler", "error"), E_ALL & ~E_NOTICE);
	set_exception_handler(array("Bolt\\Handler", "exception"));

	$config = new Bolt\Dbo\Config(array(
		"type" => DB_TYPE,
		"host" => DB_HOST,
		"port" => DB_PORT,
		"database" => DB_NAME,
		"username" => DB_USER,
		"password" => DB_PASS,
		"auto" => true
	));

	$dbo = new Bolt\Dbo($config);
	$api = new Bolt\Api($dbo);
	$api->connections->assign("dbo", 0);

	$controllerName = "Controllers\\" . $api->route->controller;

	if (class_exists($controllerName))
	{
		$controller = new $controllerName($dbo);

		if (method_exists($controller, $api->route->method))
		{
			$api->response->data = $controller->{$api->route->method}($api);
		}
	}
	elseif ($api->route->controller == "")
	{
		$config = new Bolt\Config();
		$versioning = $config->versionInfo();

		$api->response->data = array(
			"name" => API_NAME,
			"deployment" => DEPLOYMENT,
			"versioning" => $versioning['version']
		);
	}

	$api->response->output();
?>
