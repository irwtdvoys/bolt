<?php
	namespace Bolt;

	class Enum
	{
		public function expose()
		{
			return $this->identifiers();
		}

		protected function identifiers()
		{
			$refl = new \ReflectionClass($this);
			$constants = $refl->getConstants();
			asort($constants);
			return $constants;
		}
	}
?>
