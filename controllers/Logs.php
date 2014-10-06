<?php
	namespace Controllers;

	class Logs extends \Bolt\Controller
	{
		public function postSearch(\Bolt\Api $api)
		{
			if (!isset($api->request->headers->range))
			{
				$api->response->status(400, "Missing 'Range' header");
			}

			$check = $api->request->parameters->check(array("category", "event"));

			if ($check !== true)
			{
				$api->response->status(400, "Missing parameter '" . $check . "'");
			}

			$search = new \Models\Search\Logs($api->request->parameters());
			$total = $search->count();
			$range = $api->request->getRangeData($total);

			if ($range['start'] > ($total - 1) || $range['end'] < 0)
			{
				$api->response->status(416);
			}

			$number = ($range['end'] - $range['start']) + 1;

			$api->response->addHeader("Content-Range: indices " . $range['start'] . "-" . $range['end'] . "/" . $total);
			$api->response->code = ($number == $total) ? 200 : 206;

			return $search->execute($range['start'], $number);
		}
	}
?>
