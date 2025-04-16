<?php

require_once dirname(__DIR__, 3) . '/autoload.php';

use database\Sqlite;

$db = new Sqlite(dirname(__DIR__) . '/camagru.db');
$conn = $db->getConnection();

$sql = 'CREATE TABLE IF NOT EXISTS tokens (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL,
    token TEXT NOT NULL,
    type TEXT NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
)';

$conn->exec($sql);
