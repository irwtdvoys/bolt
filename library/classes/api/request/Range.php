<?php
	namespace Bolt\Api\Request;

	class Range extends \Bolt\Base
	{
		public $start;
		public $end;

		public function __construct($header = null, $total = null, $type = "indices")
		{
			if (isset($header))
			{
				$this->calculate($header, $total, $type);
			}
		}
		
		public function calculate($header, $total, $type = "indices")
		{
			if (strpos($header, $type  . "=") !== 0)
			{
				return false;
			}

			$bits = explode("-", str_replace($type . "=", "", $header));
			
			$this->start($bits[0]);
			$this->end($bits[1]);
			
			if ($total !== null)
			{
				$this->limit($total);
			}

			return true;
		}

		private function limit($total)
		{
			if ($this->end() > ($total - 1))
			{
				$this->end($total - 1);
			}

			if ($this->end() < $this->start())
			{
				$this->end($this->start());
			}
		}
		
		public function __call($name, $args)
		{
			if (!in_array($name, array("start", "end")) || $args == array())
			{
				return parent::__call($name, $args);
			}
			
			$this->$name = (int)$args[0];
			
			return true;
		}
	}
?>
