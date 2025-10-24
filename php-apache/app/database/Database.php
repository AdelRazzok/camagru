<?php

namespace database;

use PDO;

abstract class Database
{
    protected string $host;
    protected int $port;
    protected string $database;
    protected string $username;
    protected string $password;
    protected PDO $connection;

    abstract public function getConnection(): PDO;
}
