<?php
	namespace Bolt\Api\Request\Files;

	class Filter extends \Bolt\Base
	{
		public $field;
		public $mime;

		public function __construct($data)
		{
			if (is_string($data))
			{
				$data = array("field" => $data);
			}

			parent::__construct($data);
		}
	}
?>
