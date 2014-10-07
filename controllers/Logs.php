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

			$search = new \Models\Search\Logs($api->connections->dbo(), $api->request->parameters());
			$total = $search->count();
			$range = $api->request->getRangeData($total);

			if ($range->start() > max(0, ($total - 1)))
			{
				$api->response->status(416);
			}

			$number = ($range->end() - $range->start()) + 1;

			$api->response->addHeader("Content-Range: indices " . $range->start() . "-" . $range->end() . "/" . $total);
			$api->response->code = ($number == $total || $total === 0) ? 200 : 206;

			$logs = $search->execute($range->start(), $number);

			if ($logs === false)
			{
				$logs = array();
			}

			return $logs;
		}
	}
?>
