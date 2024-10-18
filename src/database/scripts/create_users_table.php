<?php

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../camagru.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    $pdo->exec($sql);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}
