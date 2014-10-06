<?php
	namespace Bolt\Api;

	class Request extends \Bolt\Base
	{
		public $files;
		public $headers;
		public $parameters;

		public function __construct()
		{
            $this->parseFiles();

            $this->headers = new Request\Headers(true);
			$this->parameters = new Request\Parameters(true);

			$this->format = isset($this->parameters->format) ? $this->parameters->format() : "json";
		}

		public function parseFiles()
		{
			if (count($_FILES) > 0)
			{
				foreach ($_FILES as $file)
				{
					$this->files[] = $file;
				}
			}
		}

		public function parameters()
		{
			return $this->parameters->parameters;
		}

		public function getRangeData($total = null, $type = "indices")
		{
			return new Request\Range($this->headers->range(), $total);
		}
	}
?>
