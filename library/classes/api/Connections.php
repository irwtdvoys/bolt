<?php
	namespace Bolt\Api;

	class Connections extends \Bolt\Base
	{
		public $sources;

		public function __construct($connections = null)
		{
			if ($connections !== null)
			{
				if (is_array($connections))
				{
					foreach ($connections as $connection)
					{
						$this->add($connection);
					}
				}
				else
				{
					$this->add($connections);
				}
			}
		}
		
		public function add($connection)
		{
			$this->sources[] = $connection;
		}
		
		public function dbo()
		{
			return $this->filter("Bolt\Dbo");
		}
		
		public function eso()
		{
			return $this->filter("Bolt\Eso");
		}
		
		private function filter($className)
		{
			$results = array();
			
			foreach ($this->sources as $source)
			{
				if ($source->className() == $className)
				{
					$results[] = $source;
				}
			}
			
			return $results;
		}
	}
?>
