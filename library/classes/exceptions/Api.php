<?php
	namespace Bolt\Exceptions;

	class Api extends \Exception
	{
		protected $codes;

		public function __construct($code, Exception $previous = null)
		{
			$codes = new \Bolt\Codes\Api();

			parent::__construct($codes->fromCode($code), $code, $previous);
		}
	}
?>
