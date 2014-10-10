<?php
	namespace Bolt\Exceptions;

	class Dbo extends \Exception
	{
		protected $codes;

		public function __construct(\PDOException $previous = null)
		{
			$info = $previous->errorInfo;
			$code = $info[1];
			$message = $info[2];

			parent::__construct($message, $code, $previous);
		}
	}
?>
