<?php
	namespace Bolt\Exceptions;

	class Curl extends \Exception
	{
		protected $codes;

		public function __construct($code, Exception $previous = null)
		{
			$codes = new \Bolt\Codes\Curl();

			parent::__construct($codes->fromCode($code), $code, $previous);
		}
	}
?>
