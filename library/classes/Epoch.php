<?php
	namespace Bolt;

	class Epoch
	{
		public static function time($precision = "s")
		{
			list($usec, $sec) = explode(" ", microtime());

			switch ($precision)
			{
				case "us":
					$dp = 6;
					break;
				case "ms":
					$dp = 3;
					break;
				default:
					$dp = 0;
					break;
			}

			$tmp = str_pad(1, (1 + $dp), 0, STR_PAD_RIGHT);
			$usec = round(($usec * $tmp), 0, PHP_ROUND_HALF_UP) / $tmp;

			$microtime = $sec . substr($usec, 2);

			return $microtime;
		}

		public static function convert($timestamp, $to = "U", $zone = false)
		{
			if (is_numeric($timestamp))
			{
				$timestamp = date("Y-m-d H:i:s", (int)$timestamp);
			}

			$dtObject = new \DateTime($timestamp);

			if ($zone === true)
			{
				$zone = "UTC";
			}

			if ($zone !== false)
			{
				$dtObject = self::shift($dtObject, $zone);
			}

			return $dtObject->format($to);
		}

		static private function shift($dtObject, $zone)
		{
			$dtObject->setTimeZone(new \DateTimeZone($zone));
			return $dtObject;
		}
	}
?>
