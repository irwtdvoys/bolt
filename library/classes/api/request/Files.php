<?php
	namespace Bolt\Api\Request;

	class Files extends \Bolt\Base
	{
		public $uploads;

		public function __construct($auto = false)
		{
			$this->uploads(array());

			if ($auto === true)
			{
				$this->parse();
			}
		}

		public function __get($name)
		{
			return $this->$name;
		}

		public function count()
		{
			return count($this->uploads());
		}

		public function add(Files\Upload $upload)
		{
			$this->uploads[] = $upload;
			return true;
		}

		public function parse()
		{
			if (count($_FILES) > 0)
			{
				// Todo: Handle the two known formats of $_FILES
				foreach ($_FILES as $file)
				{
				dump($file);
					$this->add(new Files\Upload($file));
				}
			}

			return true;
		}
	}
?>
