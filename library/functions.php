<?php
	function dump($object)
	{
		if ($object === true)
		{
			$object = "true (boolean)";
		}
		elseif ($object === false)
		{
			$object = "false (boolean)";
		}
		elseif ($object === null)
		{
			$object = "null";
		}

		echo("<pre class=\"dump\">");
		print_r($object);
		echo("</pre>");
	}

	function truncateVersion($value, $parts = 3, $keepPrefix = true)
	{
		$truncated = implode(".", array_slice(explode(".", $value), 0, $parts));

		return ($keepPrefix === true) ? $truncated : substr($truncated, 1);
	}
?>
