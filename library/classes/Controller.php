<?php
	namespace Bolt;

	class Controller extends Base
	{
		protected $dbo;
		public $userId;

		public function __construct(Dbo $dbo)
		{
			global $_USERID;
			$this->userId = $_USERID;
			$this->dbo = $dbo;
		}

		public function __toString()
		{
			return "API Object: " . $this->className();
		}

		public function patchData($model, $parameters)
		{
			foreach ($parameters as $key => $value)
			{
				if (is_scalar($value) || $value === null)
				{
					$model->$key($value);
				}
				else
				{
					$model->$key = $this->patchData($model->$key, $value);
				}
			}

			return $model;
		}
	}
?>
