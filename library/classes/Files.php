<?php
	namespace Bolt;

	class Files
	{
		public $resource = false;
		public $stats;

		public function open($filename, $mode = "w", $permissions = 0777)
		{
			$created = false;

			if ($this->resource !== false)
			{
				return false;
			}

			if (file_exists($filename) === false)
			{
				if ($mode == "r" || $mode == "r+")
				{
					return false;
				}
				else
				{
					$created = true;
					$directory = dirname($filename);

					if (is_dir($directory) === false)
					{
						mkdir($directory, $permissions, true);
						chmod($directory, $permissions);
					}
				}
			}

			$this->resource = fopen($filename, $mode);

			if ($this->resource !== false)
			{
				if ($created === true)
				{
					chmod($filename, $permissions);
				}

				$this->stats = fstat($this->resource);
			}

			return true;
		}

		public function close()
		{
			if ($this->resource === false)
			{
				return false;
			}

			if (fclose($this->resource) === true)
			{
				$this->resource = false;
				$this->stats = null;
			}

			return true;
		}

		public function write($content)
		{
			if (fwrite($this->resource, $content) === false)
			{
				return false;
			}
		}

		public function read($length = null)
		{
			if ($this->resource === false)
			{
				return false;
			}

			if ($length == null)
			{
				$length = $this->stats['size'];
			}

			return fread($this->resource, $length);
		}

		public function seek($position, $type = SEEK_SET)
		{
			if ($this->resource === false)
			{
				return false;
			}

			return (fseek($this->resource, $position, $type) == 0) ? true : false;
		}

		public function create($filename, $content, $permissions = 0777)
		{
			if ($this->resource !== false)
			{
				return false;
			}

			$this->open($filename, "w+", $permissions);
			$this->write($content);
			$this->close();

			return true;
		}

		public function load($filename)
		{
			if ($this->resource !== false)
			{
				return false;
			}

			$this->open($filename, "r");
			$content = $this->read();
			$this->close();

			return $content;
		}
	}
?>
