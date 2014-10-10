<?php
	namespace Bolt\Api;

	class Request extends \Bolt\Base
	{
		public $files;
		public $headers;
		public $parameters;

		public function __construct()
		{
			$this->headers = new Request\Headers(true);
			$this->parameters = new Request\Parameters(true);
			$this->files = new Request\Files(true);

			$this->format = isset($this->parameters->format) ? $this->parameters->format() : "json";
		}

		public function files()
		{
			return $this->files->uploads();
		}

		public function parameters()
		{
			return $this->parameters->parameters();
		}

		public function headers()
		{
			return $this->parameters->headers();
		}

		public function getRangeData($total = null, $type = "indices")
		{
			return new Request\Range($this->headers->range(), $total, $type);
		}
	}
?>
