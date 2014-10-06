<?php
	namespace Bolt;

	class Handler
	{
		private static function includeIfExists($filename)
		{
			if (file_exists($filename))
			{
				include_once($filename);
				return true;
			}

			return false;
		}

		public static function loader($className)
		{
			$namespace = explode("\\", $className);
			$className = array_pop($namespace);
			$location = "";

			if ($namespace[0] == "Bolt")
			{
				$location = "library/classes/";
				array_shift($namespace);
			}

			if (count($namespace) > 0)
			{
				$location .= implode("/", $namespace) . "/";
			}

			$result = self::includeIfExists(ROOT_SERVER . strtolower($location) . $className . ".php");

			if ($result === true)
			{
				return $result;
			}

			return false;
		}

		public static function error($level, $message, $file, $line, $context)
		{
			throw new Exceptions\Error($message, 0, $level, $file, $line);
		}

		public static function exception($exception)
		{
			$response = new Api\Response();
			$className = get_class($exception);

			if (DEPLOYMENT == "production")
			{
				$data = $className;
			}
			else
			{
				$data = array(
					"type" => $className,
					"message" => $exception->getMessage(),
					"code" => $exception->getCode(),
					"line" => $exception->getLine(),
					"file" => $exception->getFile(),
					"trace" => $exception->getTrace()
				);
			}

			$response->status(500, $data);
		}
	}
?>
