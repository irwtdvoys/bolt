<?php
	namespace Models;

	class Log extends \Bolt\Model
	{
		public $id;
		public $timestamp;
		public $category;
		public $event;
		public $data;

		public function __construct($data = false)
		{
			if (gettype($data) == "object")
			{
				if (get_class($data) == "Models\\Dbo\\Log")
				{
					$data = $data->fromDbo();
				}
			}

			parent::__construct($data);
		}

		public function audit($event, $type, $action, $id, $timestamp = null)
		{
			if ($timestamp === null)
			{
				$timestamp = \Bolt\Epoch::convert(\Bolt\Epoch::time(), "Y-m-d\TH:i:sP", "UTC");
			}

			$audit = new Log\Audit();
			$audit->type($type);
			$audit->action($action);
			$audit->id($id);

			$this->timestamp($timestamp);
			$this->category("audit");
			$this->event($event);
			$this->data($audit);

			$this->convert();
		}

		private function convert()
		{
			global $dbo;

			$record = new \Models\Dbo\Log($dbo, $this);
			return $record->save();
		}

		public function toDbo()
		{
			return (object)array(
				"id" => $this->id,
				"timestamp" => $this->timestamp,
				"category" => $this->category,
				"event" => $this->event,
				"data" => json_encode($this->data)
			);
		}
	}
?>
