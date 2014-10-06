<?php
	namespace Bolt\Curl;

	class Response extends \Bolt\Http
	{
		public $code;
		public $status;
		public $body;

		public function __construct($code, $body)
		{
			$this->code = $code;
			$this->status = $this->codeLookup($code);
			$this->body = $body;
		}
	}
?>
