<?php
	namespace Views;

	class Xml extends \Bolt\View
	{
		protected $dom;

		public function __construct()
		{
			$this->dom = new \DOMDocument("1.0", "utf-8");
		}

		public function render($content)
		{
			header("Content-type: text/xml");

			if (\Bolt\Arrays::type($content) !== false)
			{
				foreach ($content as $tmp)
				{
					$this->handleObject($tmp);
				}
			}
			else
			{
				$this->handleObject($content);
			}

			echo($this->dom->saveXML());
		}

		private function handleObject($content)
		{
			$class = get_class($content);
			$nodeName = ($class != "stdClass" && $class !== false) ? $content->className(false) : "root";

			$node = $this->dom->createElement($nodeName);
			$node = $this->createNode($content, $node);
			$this->dom->appendChild($node);
		}

		private function createNode($array, $node)
		{
			foreach ($array as $key => $value)
			{
				$key = str_replace(" ", "_", $key);

				if (is_scalar($value))
				{
					$value = !is_string($value) ? var_export($value, true) : $value;
					$element = $this->dom->createElement($key, $value);
					$node->appendChild($element);
				}
				elseif (is_array($value))
				{
					if (\Bolt\Arrays::type($value) == "numeric")
					{
						$element = $this->dom->createElement($key);

						foreach ($value as $next)
						{
							if (is_scalar($next))
							{
								$next = !is_string($next) ? var_export($next, true) : $next;
								$inner = $this->dom->createElement("value", $next);
								$element->appendChild($inner);
							}
							else
							{
								$inner = $this->dom->createElement($next->className(false));
								$inner = $this->createNode($next, $inner);
								$element->appendChild($inner);
							}
						}

						$node->appendChild($element);
					}
					else
					{
						$element = $this->dom->createElement($key);
						$element = $this->createNode($value, $element);
						$node->appendChild($element);
					}
				}
				elseif (is_object($value))
				{
					$element = $this->dom->createElement($key);
					$element = $this->createNode($value, $element);
					$node->appendChild($element);
				}
			}

			return $node;
		}
	}
?>
