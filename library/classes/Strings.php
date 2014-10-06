<?php
	namespace Bolt;

	class Strings
	{
		public static function findOverlap($str1, $str2)
		{
			$return = array();
			$sl1 = strlen($str1);
			$sl2 = strlen($str2);
			$max = ($sl1 > $sl2) ? $sl2 : $sl1;
			$i = 1;

			while ($i <= $max)
			{
				$s1 = substr($str1, $sl1-$i);
				$s2 = substr($str2, 0, $i);

				if ($s1 == $s2)
				{
					$return[] = $s1;
				}

				$i++;
			}

			if(!empty($return))
			{
				return $return;
			}

			return false;
		}

		public static function replaceOverlap($str1, $str2, $length = "long")
		{
			if ($overlap = Strings::findOverlap($str1, $str2))
			{
				switch($length)
				{
					case "short":
						$overlap = $overlap[0];
						break;
					case "long":
					default:
						$overlap = $overlap[count($overlap) - 1];
						break;
				}

				$str1 = substr($str1, 0, -strlen($overlap));
				$str2 = substr($str2, strlen($overlap));
				return $str1 . $overlap . $str2;
			}
			return false;
		}

		public static function random($length, $type = "high")
		{
			$string = "";

			switch ($type)
			{
				case "numeric":
					$characters = "0123456789";
					break;
				case "high":
					$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZacbdefghijklmnopqrstuvwxyz+=!-_";
					break;
				case "medium":
					$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZacbdefghijklmnopqrstuvwxyz";
					break;
				case "low":
				default:
					$characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZacbdefghijklmnopqrstuvwxyz";
					break;
			}

			$charLoop = 0;

			while (($charLoop < $length) && (strlen($characters) > 0))
			{
				$charLoop++;
				$character = substr($characters, mt_rand(0, strlen($characters)-1), 1);
				$string .= $character;
			}

			return $string;
		}

		public static function isRegex($string)
		{
			$regex = "/^\/[\s\S]+\/$/";
			return preg_match($regex, $string) == 1 ? true : false;
		}

		public static function isJson($string)
		{
			$result = @json_decode($string);
			return ($result === null) ? false : true;
		}
	}
?>
