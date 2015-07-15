<?php
	namespace Bolt\Api;

	class Response extends \Bolt\Http
	{
		public $headers;
		public $view;
		public $data;
		public $code;

		public function __construct()
		{
			$this->code = 200;
			$this->data = false;
			$this->headers = array();
			$this->setView("json");
		}

		public function output()
		{
			if ($this->headers === false || $this->headers === array())
			{
				$this->headers = " ";
			}

			if (!is_array($this->headers))
			{
				$this->headers = array($this->headers);
			}

			foreach ($this->headers as $header)
			{
				header($header, true, $this->code);
			}

			$group = $this->groupLookup($this->code);

			if ($this->code == 204 || $this->code == 304)
			{
				$result = null;
			}
			elseif ($this->code >= 400 || $this->data === false)
			{
				$result = array(
					$group => array(
						"code" => $this->code,
						"message" => $this->codeLookup($this->code)
					)
				);

				if ($this->data !== false)
				{
					$result[$group]['data'] = $this->data;
				}
			}
			else
			{
				$result = $this->data;
			}

			$this->view->render($result);

			die();
		}

		public function status($code, $data = false, $headers = false)
		{
			$this->code = $code;

			if ($code == 419)
			{
				$this->addHeader("HTTP/1.1 419 Authentication Timeout");
			}

			if ($data !== false)
			{
				$this->data = $data;
			}

			if ($headers !== false)
			{
				$this->setHeaders($headers);
			}

			$this->output();
		}

		public function setHeaders($headers)
		{
			$this->headers = array();

			$headers = !is_array($headers) ? array($headers) : $headers;

			foreach ($headers as $header)
			{
				$this->addHeader($header);
			}
		}

		public function addHeader($header)
		{
			$this->headers[] = (string)$header;
		}

		public function setView($format)
		{
			$viewName = "\\Views\\" . ucfirst($format);

			if (class_exists($viewName))
			{
				$this->view = new $viewName();
			}
			else
			{
				$this->status("500", "Unable to display requested view format");
			}
		}
	}
?>
