<?php
	namespace Bolt;

	class Codes extends Enum
	{
		public function fromCode($code)
		{
			$identifiers = $this->identifiers();

			if (count($identifiers) > 0)
			{
				foreach ($identifiers as $key => $value)
				{
					if ($value === $code)
					{
						return $key;
					}
				}
			}

			return "UNKNOWN";
		}
	}
?>
