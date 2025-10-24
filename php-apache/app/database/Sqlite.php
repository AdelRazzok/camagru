<?php

namespace database;

use database\Database;
use PDO;
use PDOException;

class Sqlite extends Database
{
    public function __construct(string $dbPath)
    {
        $this->host = 'sqlite:' . $dbPath;
        try {
            $this->connection = new PDO($this->host);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("SQLite connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
