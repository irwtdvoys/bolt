<?php
	namespace Controllers;

	class Health extends \Bolt\Controller
	{
		public function get(\Bolt\Api $api)
		{
			$api->response->status(200);
		}
	}
?>
