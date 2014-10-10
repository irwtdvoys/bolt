<?php
	namespace Bolt;

	class Crypto extends Base
	{
		private $mod = null; // Resource
		private $vector;
		private $vectorSize;

		private $algorithm;
		private $mode;
		private $key;

		private $base64;

		public function __construct($algorithm = "tripledes", $mode = "ecb", $key = "ABCDEFGH12345678abcdefgh", $base64 = true, $vector = null, $auto = false)
		{
			if ($this->cryptographyCheck() === false)
			{
				die("Mcrypt not available on server");
			}

			$this->setAlgorithm($algorithm);
			$this->setMode($mode);
			$this->setKey($key);
			$this->vector = $vector;

			$this->setEncoding($base64);

			if ($auto === true)
			{
				$this->open();
			}
		}

		public function __destruct()
		{
			$this->close();
		}

		public function cryptographyCheck()
		{
			return function_exists("mcrypt_encrypt") ? true : false;
		}

		public function open()
		{
			$this->mod = mcrypt_module_open($this->algorithm, "", $this->mode, "");
			$this->setVector($this->vector);
			mcrypt_generic_init($this->mod, $this->key, $this->vector);
		}

		public function close()
		{
			mcrypt_generic_deinit($this->mod);
			mcrypt_module_close($this->mod);
			$this->mod = null;
		}

		public function encrypt($text)
		{
			$data = mcrypt_generic($this->mod, $text);

			if ($this->vectorSize > 0)
			{
				$data = $this->vector . $data;
			}

			return ($this->base64 === true) ? base64_encode($data) : $data;
		}

		public function decrypt($data)
		{
			$data = ($this->base64 === true) ? base64_decode($data) : $data;

			if ($this->vectorSize > 0)
			{
				// set iv
				$this->vector = substr($data, 0, ($this->vectorSize));
				$this->close();
				$this->open();

				// remove iv
				$data = substr($data, $this->vectorSize);
			}

			return trim(mdecrypt_generic($this->mod, $data)); // trim removes padding added to the key when originally encrypted)
		}

		public function getKey()
		{
			return $this->key;
		}

		public function setKey($key)
		{
			$this->key = $key;
			return $this->getKey();
		}

		public function getMode()
		{
			return $this->mode;
		}

		public function setMode($mode)
		{
			$this->mode = $mode;
			return $this->getMode();
		}

		public function getAlgorithm()
		{
			return $this->algorithm;
		}

		public function setAlgorithm($algorithm)
		{
			$this->algorithm = $algorithm;
			return $this->getAlgorithm();
		}

		public function getVector()
		{
			return $this->vector;
		}

		public function setVector($vector = null)
		{
			$this->vector = ($vector === null) ? mcrypt_create_iv(mcrypt_enc_get_iv_size($this->mod), MCRYPT_RAND) : $vector;

			$this->vectorSize = mcrypt_enc_get_iv_size($this->mod);
			return $this->getVector();
		}

		public function getEncoding()
		{
			return $this->base64;
		}

		public function setEncoding($status)
		{
			$this->base64 = ($status === true) ? true : false;
			return $this->getEncoding();
		}
	}
?>
