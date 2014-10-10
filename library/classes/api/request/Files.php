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
				foreach ($_FILES as $field => $data)
				{
					if (is_array($data['name']))
					{
						for ($index = 0; $index < count($data['name']); $index++)
						{
							$upload = array(
								"field" => $field,
								"name" => $data['name'][$index],
								"mime" => $data['type'][$index],
								"size" => $data['size'][$index],
								"location" => $data['tmp_name'][$index],
								"error" => $data['error'][$index]
							);

							$this->add(new Files\Upload($upload));
						}
					}
					else
					{
						$upload = array(
							"field" => $field,
							"name" => $data['name'],
							"mime" => $data['type'],
							"size" => $data['size'],
							"location" => $data['tmp_name'],
							"error" => $data['error']
						);

						$this->add(new Files\Upload($upload));
					}
				}
			}

			return true;
		}

		public function filter(Files\Filter $filter)
		{
			$results = array();

			foreach ($this->uploads() as $next)
			{
				$matched = true;

				foreach ($filter as $key => $value)
				{
					if ($value !== null)
					{
						if ($next->{$key}() != $value)
						{
							$matched = false;
						}
					}
				}

				if ($matched === true)
				{
					$results[] = $next;
				}
			}

			return $results;
		}
	}
?>
