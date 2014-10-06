<?php
	namespace Bolt\Model;

	class Dbo extends \Bolt\Model
	{
		protected $dbo;
		private $new = false;
		protected $table = null;

		public function __construct(\Bolt\Dbo $dbo = null, $data = false)
		{
			if ($dbo === null)
			{
				global $dbo;
			}

			$this->dbo = $dbo; //\Bolt\Dbo

			parent::__construct($data);
		}

		public function parseParameters($parameters)
		{
			$values = array();
			$fields = array();

			foreach ($parameters as $key => $value)
			{
				$fields[] = $key;
				$values[":" . $key] = $value;
			}

			return array($fields, $values);
		}

		public function getQuerySetString($fields)
		{
			$setString = "";

			for ($loop = 0; $loop < count($fields); $loop++)
			{
				$key = $fields[$loop];

				if ($loop > 0)
				{
					$setString .= ", ";
				}

				$setString .= "`" . $key . "` = :" . $key;
			}

			return $setString;
		}

		protected function insert($parameters, $return = true)
		{
			list($fields, $values) = $this->parseParameters($parameters);

			$SQL = "INSERT INTO `" . $this->table . "` (`" . implode("`,`", $fields) . "`)
					VALUES (:" . implode(", :", $fields) . ")";
			return $this->dbo->query($SQL, $values, $return);
		}

		protected function update($parameters)
		{
			list($fields, $values) = $this->parseParameters($parameters);

			$fields = \Bolt\Arrays::removeElement("id", $fields);
			$setString = $this->getQuerySetString($fields);

			$SQL = "UPDATE `" . $this->table . "`
					SET " . $setString . "
					WHERE `id` = :id";
			return $this->dbo->query($SQL, $values);
		}

		protected function get($id)
		{
			$SQL = "SELECT * FROM `" . $this->table . "` WHERE `id` = :id LIMIT 0, 1";
			return $this->dbo->query($SQL, array(":id" => $this->id()), true);
		}

		public function load()
		{
			$result = $this->get($this->id());

			if ($result === false)
			{
				return false;
			}

			$this->populate($result);

			return true;
		}

		public function save()
		{
			$properties = $this->getProperties();

			foreach ($properties as $property)
			{
				$parameters[$property->name] = $this->{$property->name};
			}

			$result = $this->get($this->id());

			if ($result === false)
			{
				$record = $this->insert($parameters);
			}
			else
			{
				$record = $this->update($parameters);
			}

			if ($record !== false)
			{
				$this->merge($record, null, true);
				return true;
			}

			return $record;
		}

		public function delete()
		{
			$SQL = "DELETE FROM `" . $this->table . "` WHERE `id` = :id";
			return $this->dbo->query($SQL, array(":id" => $this->id()));
		}
	}
?>
