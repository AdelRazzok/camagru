<?php

require_once dirname(__DIR__, 3) . '/autoload.php';

use database\SQLite\Sqlite;

$db = new Sqlite(dirname(__DIR__) . '/camagru.db');
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS images (
    id INTEGER PRIMARY KEY,
    file_name TEXT NOT NULL,
    mime_type TEXT NOT NULL,
    image_data BLOB NOT NULL,
    image_size INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
)";

$conn->exec($sql);
