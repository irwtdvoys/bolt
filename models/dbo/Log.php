<?php
	namespace Models\Dbo;

	class Log extends \Bolt\Model\Dbo
	{
		public $id;
		public $timestamp;
		public $category;
		public $event;
		public $data;

		protected $new = false;

		public function __construct($dbo = null, $data = false)
		{
			if (gettype($data) == "object")
			{
				if (strpos(get_class($data), "Models\\Log") === 0)
				{
					$data = $data->toDbo();
				}
			}

			parent::__construct($dbo, $data);

			$this->table = "logs";
		}

		public function fromDbo()
		{
			$result = (object)array(
				"id" => $this->id,
				"timestamp" => $this->timestamp,
				"category" => $this->category,
				"event" => $this->event,
				"data" => json_decode($this->data)
			);

			switch ($result->category)
			{
				case "audit":
					$result->data = new \Models\Log\Audit($result->data);
					break;
			}

			return $result;
		}
	}
?>
