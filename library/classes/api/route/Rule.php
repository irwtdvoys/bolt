<?php
	namespace Bolt\Api\Route;

	class Rule extends \Bolt\Base
	{
		public $request;
		public $route;

		public function __construct($data)
		{
			$this->request['controller'] = $this->parse($data->request->controller);
			$this->request['id'] = $this->parse($data->request->id);
			$this->request['action'] = $this->parse($data->request->action);

			$this->route['controller'] = $data->route->controller;
			$this->route['method'] = $data->route->method;
		}

		public function check($info)
		{
			if ($info->controller === null)
			{
				return false;
			}

			foreach ($this->request as $key => $value)
			{
				if ($this->request[$key] !== null)
				{
					if ($info->$key === null)
					{
						return false;
					}

					if (\Bolt\Strings::isRegex($this->request[$key]) === true)
					{
						if (preg_replace($this->request[$key], "", $info->$key) !== "")
						{
							return false;
						}
					}
					else
					{
						if ($this->request[$key] !== $info->$key)
						{
							return false;
						}
					}
				}
			}

			return true;
		}

		public function route($info)
		{
			return array(
				"controller" => $this->parse($this->route['controller'], $info),
				"method" => $this->parse($this->route['method'], $info)
			);
		}

		private function parse($string, $info = false)
		{
			$string = $this->wildcardToRegex($string, $info);
			$string = $this->applyFunctions($string);
			return ($string === "") ? null : $string;
		}

		private function wildcardToRegex($string, $info = false)
		{
			$wildcards = array(
				"num" => "/[0-9]/",
				"alpha" => "/[A-z]/",
				"any" => "/[A-z0-9-\.]/"
			);

			if (is_object($info))
			{
				$info = (array)$info;
			}

			if (is_array($info))
			{
				$wildcards = array_merge($wildcards, $info);
			}

			foreach ($wildcards as $key => $regex)
			{
				$string = str_replace("(:" . $key . ")", $regex, $string);
			}

			return $string;
		}

		private function applyFunctions($string)
		{
			$string = preg_replace_callback(
				"/\[upper\|(.*?)\]/",
				function ($matches)
				{
					return strtoupper($matches[1]);
				},
				$string
			);
			$string = preg_replace_callback(
				"/\[lower\|(.*?)\]/",
				function ($matches)
				{
					return strtolower($matches[1]);
				},
				$string
			);
			$string = preg_replace_callback(
				"/\[first\|(.*?)\]/",
				function ($matches)
				{
					return ucwords($matches[1]);
				},
				$string
			);
			return $string;
		}
	}
?>
