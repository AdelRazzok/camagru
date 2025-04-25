<?php

namespace database;

use PDO;
use PDOException;

class Postgresql extends Database
{
	public function __construct(string $host, int $port)
	{
		$this->host = $host;
		$this->port = $port;
		$this->database = getenv('DB_NAME');
		$this->username = getenv('POSTGRES_USER');
		$this->password = getenv('POSTGRES_PASSWORD');

		$dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->database";

		try {
			$this->connection = new PDO($dsn, $this->username, $this->password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die("PostgreSQL connection failed: " . $e->getMessage());
		}
	}

	public function getConnection(): PDO
	{
		return $this->connection;
	}
}
