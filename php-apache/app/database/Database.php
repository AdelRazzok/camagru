<?php

namespace database;

use PDO;

class Database
{
    protected string $host;
    protected string $database;
    protected string $username;
    protected string $password;
    protected PDO $connection;
}
