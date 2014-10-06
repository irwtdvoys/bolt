<?php
	namespace Bolt\Api;

	class Authorization extends \Bolt\Base
	{
		public $scheme;
		public $parameters;

		public function parse($header)
		{
			list($scheme, $data) = explode(" ", $header, 2);

			if ($scheme == $header)
			{
				$scheme = "Local";
				$data = $header;
			}

			$parameters = explode(",", $data);
			$results = array();

			$this->scheme($scheme);

			foreach ($parameters as $parameter)
			{
				$tmp = strpos($parameter, "=");

				if ($tmp === false || ($tmp == (strlen($parameter) - 1) || $tmp == (strlen($parameter) - 2)) && $parameter[strlen($parameter) - 1] == "=")
				{
					$results[] = $parameter;
				}
				else
				{
					list($key, $value) = explode("=", $parameter, 2);

					$key = trim($key);
					$value = trim($value, '"');

					$results[$key] = $value;
				}
			}

			if (count($results) == 1)
			{
				if ($results[0] == $data)
				{
					$results = array("token" => $data);
				}
			}

			$this->parameters((object)$results);
		}

		public function token()
		{
			return $this->parameters->token;
		}
	}
?>
