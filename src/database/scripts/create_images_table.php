<?php

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../camagru.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS images (
        id INTEGER PRIMARY KEY,
        file_name TEXT NOT NULL,
        mime_type TEXT NOT NULL,
        image_data BLOB NOT NULL,
        image_size INTEGER NOT NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL
    )";
    $pdo->exec($sql);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
