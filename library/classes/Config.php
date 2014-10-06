<?php
	namespace Bolt;

	class Config
	{
		private $constants;

		public function __construct()
		{
			$this->constants = get_defined_constants(true);
		}

		public function getByName($constant)
		{
			return $this->constants['user'][$constant];
		}

		public function getByPrefix($prefix)
		{
			$results = array();

			if (count($this->constants['user']) > 0)
			{
				foreach ($this->constants['user'] as $key => $value)
				{
					if (strpos($key, $prefix) === 0)
					{
						$results[$key] = $value;
					}
				}
			}

			return $results;
		}

		private function inflate($data, $limit = false, $depth = 0)
		{
			$results = array();

			if (count($data) > 0)
			{
				foreach ($data as $key => $value)
				{
					list($head, $tail) = explode("_", strtolower($key), 2);

					if ($tail == "")
					{
						$results[$key] = $value;
					}
					else
					{
						$results[$head][$tail] = $value;
					}
				}

				if ($limit === false || $depth < $limit)
				{
					foreach ($results as &$next)
					{
						if (count($next) > 1)
						{
							$next = $this->inflate($next, $limit, ($depth + 1));
						}
					}
				}
			}

			return $results;
		}

		public function versionInfo()
		{
			return $this->inflate($this->getByPrefix("VERSION_"));
		}
	}
?>
