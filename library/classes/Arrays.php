<?php
	namespace Bolt;

	class Arrays
	{
		public static function removeElement($needle, $haystack)
		{
			return array_values(array_diff($haystack, array($needle)));
		}

		public static function reKey($array)
		{
			$results = array();

			foreach ($array as $element)
			{
				$results[] = $element;
			}

			return $results;
		}

		public static function subValueSort($array, $subkey, $order = "ASC")
		{
			foreach($array as $key => $value)
			{
				$subArray[$key] = strtolower($value[$subkey]);
			}

			if ($order == "ASC")
			{
				asort($subArray);
			}
			else
			{
				arsort($subArray);
			}

			foreach($subArray as $key => $val)
			{
				$results[] = $array[$key];
			}

			return $results;
		}

		public static function type($array)
		{
			if (!is_array($array))
			{
				$result = false;
			}
			elseif (array_values($array) === $array)
			{
				$result = "numeric";
			}
			else
			{
				$result = "assoc";
			}

			return $result;
		}
	}
?>
