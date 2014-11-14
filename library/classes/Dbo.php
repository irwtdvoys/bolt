<?php
	namespace Bolt;

	class Dbo extends Base
	{
		protected $connection;
		public $config = array();

		public function __construct($config = null)
		{
			if ($config !== null)
			{
				$this->config(new Dbo\Config($config));

				if ($this->config->auto() === true)
				{
					$this->connect();
				}
			}
		}

		public function __destruct()
		{
			$this->disconnect();
		}

		public function ping()
		{
			if ($this->connection === null)
			{
				return false;
			}

			try
			{
				$this->connection->query("DO 1");
			}
			catch (Exception $exception)
			{
				return false;
			}

			return true;
		}

		public function connect()
		{
			$options = array();

			switch ($this->config->type())
			{
				case "mysql":
					$dsn = $this->config->type() . ":host=" . $this->config->host() . ";port=" . $this->config->port() . ";dbname=" . $this->config->database() . ";charset=utf8";
					$options = array(
						\PDO::ATTR_EMULATE_PREPARES => false,
						\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
						\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
					);
					break;
				case "sqlite":
					$dsn = $this->config->type() . ":" . $this->config->database();
					break;
			}

			try
			{
				$this->connection = new \PDO($dsn, $this->config->username(), $this->config->password(), $options);
			}
			catch (\PDOException $error)
			{
				throw new Exceptions\Dbo($error);
			}
		}

		public function disconnect()
		{
			$this->connection = null;
		}

		public function query($SQL, $parameters = array(), $return = false, $style = \PDO::FETCH_ASSOC, $argument = null)
		{
			if ($this->connection == "")
			{
				throw new Exceptions\Dbo();
			}

			$results = array();

			try
			{
				$statement = $this->connection->prepare($SQL);
			}
			catch (\PDOException $error)
			{
				throw new Exceptions\Dbo($error);
			}

			if (!is_array(reset($parameters)))
			{
				$parameters = array($parameters);
				$single = true;
			}
			else
			{
				$single = false;
			}

			for ($loop = 0; $loop < count($parameters); $loop++)
			{
				$values = $parameters[$loop];
				$arrayType = ($values == array_values($values)) ? "NUM" : "ASSOC";

				if (count($values) > 0)
				{
					foreach ($values as $key => $value)
					{
						$paramType = $this->getParameterType($value);

						$id = $key;

						if ($arrayType != "ASSOC")
						{
							$id = $key + 1;
						}

						try
						{
							$statement->bindParam($id, $values[$key], $paramType);
						}
						catch (\PDOException $error)
						{
							throw new Exceptions\Dbo($error);
						}
					}
				}

				try
				{
					$statement->execute();
				}
				catch (\PDOException $error)
				{
					throw new Exceptions\Dbo($error);
				}
			}

			$queryType = strtoupper(substr($SQL, 0, strpos($SQL, " ")));

			if ($queryType == "SELECT" || $queryType == "SHOW")
			{
				switch ($style)
				{
					case \PDO::FETCH_CLASS:
					case \PDO::FETCH_COLUMN:
					case \PDO::FETCH_FUNC:
						$results = $statement->fetchAll($style, $argument);
						break;
					default:
						$results = $statement->fetchAll($style);
						break;
				}

				if (count($results) == 0)
				{
					$results = false;
				}
				elseif ($return === true && $single === true)
				{
					$results = $results[0];
				}
			}
			elseif ($queryType == "INSERT" && $return === true && $single === true)
			{
				$id = $this->connection->lastInsertId();
				$table = substr($SQL, 12, strpos($SQL, " ", 12) - 12);
				$index = $this->query("SHOW INDEX FROM `" . $this->config->database() . "`." . $table . " WHERE `Key_name` = 'PRIMARY'", array(), true); // possible security issue here as the statement ?cant? be prepared
				$key = $index['Column_name'];

				$SQL = "SELECT * FROM " . $table . " WHERE " . $key . " = " . $id;
				$results = $this->query($SQL, array(), true, $style, $argument);
			}

			return $results;
		}

		private function getParameterType($value)
		{
			if ($value === true || $value === false)
			{
				$type = \PDO::PARAM_BOOL;
			}
			elseif ($value === null)
			{
				$type = \PDO::PARAM_NULL;
			}
			elseif (is_int($value))
			{
				$type = \PDO::PARAM_INT;
			}
			else
			{
				$type = \PDO::PARAM_STR;
			}

			return $type;
		}

		public function interpolate($SQL, $parameters)
		{
			$keys = array();

			foreach($parameters as $key => $value)
			{
				$keys[] = is_string($key) ? "/" . $key . "/" : "/[?]/";

				if (is_int($value))
				{
					$parameters[$key] = $value;
				}
				elseif (is_bool($value))
				{
					$parameters[$key] = ($value) ? "TRUE" : "FALSE";
				}
				elseif ($value === null)
				{
					$parameters[$key] = "NULL";
				}
				else
				{
					$parameters[$key] = "'" . $value . "'";
				}
			}

			$query = preg_replace($keys, $parameters, $SQL, 1);

			return $query;
		}
	}
?>
