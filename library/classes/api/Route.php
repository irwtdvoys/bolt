<?php
	namespace Bolt\Api;

	class Route extends \Bolt\Base
	{
		private $rules;

        public $info;

		public $controller;
		public $method;

		public function __construct($auto = false)
		{
			$this->info = new Route\Info($auto);

			if ($auto === true)
			{
				$this->load();
			}
		}

		public function load()
		{
			$this->loadRules();

			if (count($this->rules) > 0)
			{
				foreach ($this->rules as $next)
				{
					if ($next->check($this->info) === true)
					{
						$data = $next->route($this->info);
						break;
					}
				}
			}

			$this->controller = $data['controller'];
			$this->method = $data['method'];
		}

		private function loadRules()
		{
			$fileHandler = new \Bolt\Files();

			$config = json_decode($fileHandler->load(ROOT_SERVER . "/library/routes.json"));

			if (count($config) > 0)
			{
				foreach ($config as $rule)
				{
					$this->rules[] = new Route\Rule($rule);
				}
			}
		}
	}
?>
