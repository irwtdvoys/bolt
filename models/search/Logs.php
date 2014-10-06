<?php
	namespace Models\Search;

	class Logs extends \Bolt\Model
	{
		public $range;
		public $category;
		public $event;

		private $dbo;

		public function __construct($data = false)
		{
			global $dbo;

			$this->dbo = $dbo;

			parent::__construct($data);

			$this->range = new \Models\Range($this->range);

			// Force to UTC + set defaults
			$this->range->from = isset($this->range->from) ? \Bolt\Epoch::convert($this->range->from, "Y-m-d\TH:i:sP", "UTC") : "2014-08-10T00:00:00+00:00";
			$this->range->to = \Bolt\Epoch::convert(isset($this->range->to) ? $this->range->to : \Bolt\Epoch::time(), "Y-m-d\TH:i:sP", "UTC");
		}

		public function execute($from, $quantity)
		{
			$SQL = "SELECT * FROM `logs` WHERE `category` = :category AND `event` = :event AND `timestamp` BETWEEN :from AND :to ORDER BY `timestamp` DESC LIMIT :start, :quantity";
			$records = $this->dbo->query($SQL, $this->fields(array("from" => $from, "quantity" => $quantity)), false, \PDO::FETCH_CLASS, "\\Models\\Dbo\\Log");

			foreach ($records as &$record)
			{
				$record = $record->fromDbo();
			}

			return $records;
		}

		public function count()
		{
			$SQL = "SELECT COUNT(*) AS 'total' FROM `logs` WHERE `category` = :category AND `event` = :event AND `timestamp` BETWEEN :from AND :to";
			$result = $this->dbo->query($SQL, $this->fields(), true);

			return $result['total'];
		}

		private function fields($limits = null)
		{
			$fields = array(
				":category" => $this->category(),
				":event" => $this->event(),
				":from" => $this->range->from(),
				":to" => $this->range->to()
			);

			if ($limits !== null)
			{
				$fields[':start'] = $limits['from'];
				$fields[':quantity'] = $limits['quantity'];
			}

			return $fields;
		}
	}
?>
