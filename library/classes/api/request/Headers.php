<?php
	namespace Bolt\Api\Request;

	class Headers extends \Bolt\Base
	{
		private $headers;

		public function __construct($auto = false)
		{
			if ($auto === true)
			{
				$this->parse();
			}
		}

		public function __get($name)
		{
			return $this->$name;
		}

		public function __isset($name)
		{
			return isset($this->headers[$name]) ? true : false;
		}

		public function __call($name, $arguments)
		{
			if ($arguments == array())
			{
				return $this->headers[$name];
			}

			$this->headers[$name] = $arguments[0];
			return true;
		}

		public function parse()
		{
            $this->headers = array_change_key_case(apache_request_headers(), CASE_LOWER);
		}
	}
?>
