<?php
	namespace Bolt;

	class View
	{
		public function className()
		{
			return get_class($this);
		}

		public function __toString()
		{
			return "API Object: " . $this->className();
		}
	}
?>
