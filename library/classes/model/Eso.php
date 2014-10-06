<?php
	namespace Bolt\Model;

	class Eso extends \Bolt\Model
	{
		protected $eso;
		private $id;

		private $new = false;

		public function __construct(\Bolt\Eso $eso, $data = false)
		{
			$this->eso = $eso;

			parent::__construct($data);
		}

		public function load($filter = "")
		{
			$this->eso->configure($this->id() . $filter, "get");
			$result = $this->eso->execute();

			if ($result->code != 200)
			{
				global $api;
				$api->response->status($result->code);
			}

			$this->fromStructure($result->body->_source);

			return $result->code;
		}

		public function save()
		{
			$preset = ($this->new === true) ? "insert" : "update";

			$this->eso->configure($this->id(), $preset, $this->toJson("es"));
			$result = $this->eso->execute();

			return $result;
		}

		public function id($id = false)
		{
			if ($id === false)
			{
				return $this->id;
			}
			elseif ($id === true)
			{
				$id = $this->generateId();
				$this->new = true;
			}

			$this->id = $id;
		}
	}
?>
