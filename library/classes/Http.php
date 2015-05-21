<?php
	namespace Bolt;

	class Http extends Base
	{
		public function groupLookup($code)
		{
			if ($code < 200)
			{
				$group = "informational";
			}
			elseif ($code < 300)
			{
				$group = "success";
			}
			elseif ($code < 400)
			{
				$group = "redirection";
			}
			elseif ($code < 500)
			{
				$group = "client error";
			}
			elseif ($code < 600)
			{
				$group = "server error";
			}
			else
			{
				$group = "unknown";
			}

			return $group;
		}

		public function codeLookup($code)
		{
			switch ($code)
			{
				case 100:
					$result = "Continue";
					break;
				case 101:
					$result = "Switching Protocols";
					break;
				case 102:
					$result = "Processing";
					break;

				case 200:
					$result = "OK";
					break;
				case 201:
					$result = "Created";
					break;
				case 202:
					$result = "Accepted";
					break;
				case 203:
					$result = "Non-Authoritative Information";
					break;
				case 204:
					$result = "No Content";
					break;
				case 205:
					$result = "Reset Content";
					break;
				case 206:
					$result = "Partial Content";
					break;

				case 300:
					$result = "Multiple Choices";
					break;
				case 301:
					$result = "Moved Permanently";
					break;
				case 302:
					$result = "Found";
					break;
				case 303:
					$result = "See Other";
					break;
				case 304:
					$result = "Not Modified";
					break;
				case 305:
					$result = "Use Proxy";
					break;
				case 306:
					$result = "Switch Proxy";
					break;
				case 307:
					$result = "Temporary Redirect";
					break;
				case 308:
					$result = "Permanent Redirect";
					break;

				case 400:
					$result = "Bad Request";
					break;
				case 401:
					$result = "Unauthorized";
					break;
				case 402:
					$result = "Payment Required";
					break;
				case 403:
					$result = "Forbidden";
					break;
				case 404:
					$result = "Not Found";
					break;
				case 405:
					$result = "Method Not Allowed";
					break;
				case 406:
					$result = "Not Acceptable";
					break;
				case 407:
					$result = "Proxy Authentication Required";
					break;
				case 408:
					$result = "Request Timeout";
					break;
				case 409:
					$result = "Conflict";
					break;
				case 410:
					$result = "Gone";
					break;
				case 411:
					$result = "Length Required";
					break;
				case 412:
					$result = "Precondition Failed";
					break;
				case 413:
					$result = "Request Entity Too Large";
					break;
				case 414:
					$result = "Request-URI Too Long";
					break;
				case 415:
					$result = "Unsupported Media Type";
					break;
				case 416:
					$result = "Requested Range Not Satisfiable";
					break;
				case 417:
					$result = "Expectation Failed";
					break;
				case 419:
					$result = "Authentication Timeout";
					break;
				case 422:
					$result = "Unprocessable Entity";
					break;
				case 423:
					$result = "Locked";
					break;
				case 426:
					$result = "Upgrade Required";
					break;
				case 428:
					$result = "Precondition Required";
					break;
				case 429:
					$result = "Too Many Requests";
					break;
				case 431:
					$result = "Request Header Fields Too Large";
					break;

				case 500:
					$result = "Internal Server Error";
					break;
				case 501:
					$result = "Not Implemented";
					break;
				case 502:
					$result = "Bad Gateway";
					break;
				case 503:
					$result = "Service Unavailable";
					break;
				case 504:
					$result = "Gateway Timeout";
					break;
				case 505:
					$result = "HTTP Version Not Supported";
					break;
				case 506:
					$result = "Variant Also Negotiates";
					break;
				case 507:
					$result = "Insufficient Storage";
					break;
				case 508:
					$result = "Loop Detected";
					break;
				case 509:
					$result = "Bandwidth Limit Exceeded";
					break;
				case 510:
					$result = "Not Extended";
					break;
				case 511:
					$result = "Network Authentication Required";
					break;

				default:
					$result = "Unknown Response";
					break;
			}

			return $result;
		}
	}
?>
