<?php
	namespace Bolt\Api\Request\Files;

	class Upload extends \Bolt\Base
	{
		public $field;
		public $name;
		public $mime;
		public $size;
		public $location;
		public $error;

		public function exists()
		{
			return file_exists($this->location());
		}
	}
?>
