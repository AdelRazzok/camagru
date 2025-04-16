<?php

require_once dirname(__DIR__, 3) . '/autoload.php';

use database\Sqlite;

$db = new Sqlite(dirname(__DIR__) . '/camagru.db');
$conn = $db->getConnection();

$sql = 'CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    email TEXT NOT NULL,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    email_verified BOOLEAN NOT NULL DEFAULT 0,
    email_notif_on_comment BOOLEAN NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
)';

$conn->exec($sql);
