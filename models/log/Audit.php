<?php
	namespace Models\Log;

	class Audit extends \Bolt\Model
	{
		public $type;
		public $action;
		public $id;
	}
?>
