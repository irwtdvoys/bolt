<?php
	namespace Bolt\Api;

	class Request
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
			if (strpos($this->headers->range(), $type  . "=") !== 0)
			{
				return false;
			}

			list($range['start'], $range['end']) = explode("-", str_replace($type . "=", "", $this->headers->range()));

			if ($total !== null)
			{
				if ($range['end'] > ($total - 1))
				{
					$range['end'] = $total - 1;
				}
			}

			return $range;
		}
	}
?>
