<?php
	namespace Bolt;

	class Maths
	{
		public static function _double($value, $iterations = 1)
		{
			$result = $value;

			if ($iterations > 0)
			{
				$result = self::_double($result * 2, ($iterations - 1));
			}

			return $result;
		}
	}
?>
