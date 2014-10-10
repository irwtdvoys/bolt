<?php
	namespace Bolt;

	class View extends Base
	{
		public function __toString()
		{
			return "API Object: " . $this->className();
		}
	}
?>
