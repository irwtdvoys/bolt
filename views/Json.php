<?php
	namespace Views;

	class Json extends \Bolt\View
	{
		public function render($content)
		{
			header("Content-Type: application/json; charset=UTF-8");

			if ($content !== null)
			{
				echo($this->handleObject($content));
			}

			/*$type = \Bolt\Arrays::type($content);

			if ($type == "numeric")
			{
				foreach ($content as $tmp)
				{
					$this->handleObject($tmp);
				}
			}
			elseif ($type == "assoc" || get_class($content) == "stdClass")
			{
				echo(json_encode($content));
			}
			else
			{
				echo($content->toJson());
			}*/

			return true;
		}

		private function handleObject($content)
		{
			$type = \Bolt\Arrays::type($content);

			if ($type == "numeric")
			{
				$results = array();

				foreach ($content as $tmp)
				{
					if (\Bolt\Arrays::type($tmp) !== false || get_class($tmp) == "stdClass") // error here with arrays of string?
					{
						$results = $content;
					}
					else
					{
						$results[] = json_decode($tmp->toJson());
					}
				}

				return json_encode($results);
			}
			elseif ($type != "assoc" && get_class($content) != "stdClass")
			{
				return $content->toJson();
			}
			else
			{
				return json_encode($content);
			}
		}
	}
?>
