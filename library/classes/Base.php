<?php
	namespace Bolt;

	class Base
	{
		public function __construct($data = null)
		{
			if ($data !== null)
			{
				$this->populate($data);
			}
		}

		private function populate($data)
		{
			$properties = $this->getProperties();

			if (count($properties) > 0)
			{
				foreach ($properties as $property)
				{
					$value = is_array($data) ? $data[$property->name] : $data->{$property->name};
					$this->{$property->name}($value);
				}
			}

			return true;
		}

		protected function getProperties()
		{
			$reflection = new \ReflectionClass($this->className());
			return $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
		}

		public function className($full = true)
		{
			$className = get_class($this);

			if ($full === false)
			{
				$namespace = explode("\\", $className);
				$className = $namespace[count($namespace) - 1];
			}

			return $className;
		}

		protected function calculateNamespace($object)
		{
			$namespace = array(
				__NAMESPACE__,
				$this->className(),
				ucwords($object->class)
			);

			$namespace = array_values(array_filter($namespace));
			$className = implode("\\", $namespace);

			return $className;
		}

		public function __call($name, $args)
		{
			if ($args == array())
			{
				return $this->$name;
			}

			$this->$name = $args[0];
			return true;
		}
	}
?>
