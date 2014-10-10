<?php
	namespace Bolt\Api\Route;

	class Info extends \Bolt\Base
	{
		public $verb;
		public $controller;
		public $id;
		public $action;

		public function __construct($auto = false)
		{
			if ($auto === true)
			{
				$this->verb = $_SERVER['REQUEST_METHOD'];

				$urlElements = explode("/", $this->parseUrl());

				$this->controller = isset($urlElements[0]) ? $urlElements[0] : null;
				$this->id = isset($urlElements[1]) ? $urlElements[1] : null;
				$this->action = isset($urlElements[2]) ? $urlElements[2] : null;
			}
		}

        private function parseUrl()
        {
            $uri = !empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'];

            $adjustedRoot = (strpos($uri, "/latest") === 0) ? str_replace(VERSION_INTERNAL_API, "latest", ROOT_API): ROOT_API;
            $fullRequestPath = \Bolt\Strings::replaceOverlap($adjustedRoot, $uri);
            $path = str_replace($adjustedRoot, "", $fullRequestPath);

            $bits = explode("/", $path);

            for ($loop = 0; $loop < count($bits); $loop++)
            {
                $bit = $bits[$loop];

                if ($bit == "")
                {
                    unset($bits[$loop]);
                }
            }

            $path = implode("/", $bits);
            $divider = strpos($path, "?");

            if ($divider === false)
            {
                $info['baseUrl'] = $path;
            }
            else
            {
                list($info['baseUrl'], $info['queryString']) = explode("?", $path);
            }

            return $info['baseUrl'];
        }
	}
?>
