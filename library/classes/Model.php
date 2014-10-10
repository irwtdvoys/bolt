<?php
	namespace Bolt;

	class Model extends Base
	{
		public function __construct($data = null)
		{
			$this->populate($data);
		}

		public function __toString()
		{
			$results = null;
			$properties = $this->getProperties();
			#$results['class'] = $this->className(false);

			foreach ($properties as $property)
			{
				$value = $this->{$property->name};

				if ($value !== null)
				{
					if (is_array($value))
					{
						foreach ($value as &$element)
						{
							if (is_object($element) && get_class($element) != "stdClass")
							{
								$element = json_decode((string)$element);
							}
						}
					}

					if (is_object($value))
					{
						if (get_class($value) != "stdClass")
						{
							$value = json_decode((string)$value);
						}
					}

					$results[$property->name] = $value;
				}
			}

			return json_encode($results);
		}

		public function toJson($type = "api")
		{
			$results = null;
			$properties = $this->getProperties();
			#$results['class'] = $this->className(false);

			$id = $this->id();

			if ($id != null && $results['class'] != "User" && $type != "es")
			{
				$results['id'] = $id;
			}

			foreach ($properties as $property)
			{
				$value = $this->{$property->name};

				if ($value !== null)
				{
					if (is_array($value))
					{
						foreach ($value as &$element)
						{
							if (is_object($element) && get_class($element) != "stdClass")
							{
								$element = json_decode($element->toJson());
							}
						}

						if ($value === array())
						{
							$value = null;
						}
					}

					if (is_object($value))
					{
						if (get_class($value) != "stdClass")
						{
							$value = json_decode($value->toJson());
						}
					}

					if ($value !== null)
					{
						$results[$property->name] = $value;
					}
				}
			}

			return json_encode($results);
		}

		public function populate($data = null)
		{
			if ($data !== null)
			{
				if (is_string($data))
				{
					$data = json_decode($data);
				}

				if (is_array($data) || is_object($data))
				{
					$this->fromStructure($data);
				}
			}
		}

		public function merge($parsed, $node = null, $override = false)
		{
			if ($node === null)
			{
				$node = &$this;
			}

			foreach ($parsed as $key => $value)
			{
				if (isset($node->$key))
				{
					if (is_object($node->$key) && is_object($parsed->$key))
					{
						$node->merge($parsed->$key, $node->$key, $override);
					}
					elseif ($override === true)
					{
						$node->$key = $value;
					}
				}
				else
				{
					if (is_object($parsed->$key))
					{
						$node->$key = (object)array();
						$node->merge($parsed->$key, $node->$key, $override);
					}
					else
					{
						$node->$key = $value;
					}
				}
			}
		}

		public function fromStructure($data)
		{
			foreach ($data as $property => $content)
			{
				if (property_exists($this, $property))
				{
					if (is_array($content))
					{
						foreach ($content as $key => $value)
						{
							if (is_object($value) && !empty($value->class))
							{
								$className = $this->className() . "\\" . $value->class;
								$this->{$property}[$key] = new $className($value);
							}
							else
							{
								$this->{$property}[$key] = $value;
							}
						}
					}
					elseif (is_object($content) && !empty($content->class))
					{
						$className = $this->className() . "\\" . $content->class;
						$this->$property = new $className($content);
					}
					else
					{
						$this->$property = $content;
					}
				}
			}
		}

		public function output()
		{
			return json_decode((string)$this);
		}

		public function isEmpty()
		{
			$properties = $this->getProperties();

			foreach ($properties as $property)
			{
				$data = $this->{$property->name};

				if (gettype($data) == "object")
				{
					if (strpos(get_class($data), "Models\\") === 0)
					{
						$result = $data->isEmpty();
					}
				}
				elseif (gettype($data) == "array")
				{
					if (count($data) > 0)
					{
						foreach ($data as $next)
						{
							if (gettype($next) == "object")
							{
								if (strpos(get_class($next), "Models\\") === 0)
								{
									if ($next->isEmpty() === false)
									{
										$result = false;
									}
								}
							}
							elseif ($next !== null)
							{
								$result = false;
							}

							if ($result === false)
							{
								return false;
							}
						}
					}
				}
				elseif ($data !== null)
				{
					$result = false;
				}

				if ($result === false)
				{
					return false;
				}
			}

			return true;
		}
	}
?>
