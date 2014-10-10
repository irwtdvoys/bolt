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

		public function hash($algorithm = "md5")
		{
			return hash($algorithm, file_get_contents($this->location()));
		}

		public function exists()
		{
			return file_exists($this->location());
		}
	}
?>
