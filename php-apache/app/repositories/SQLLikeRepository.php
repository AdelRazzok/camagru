<?php

namespace repositories;

use PDO;
use repositories\interfaces\LikeRepositoryInterface;

class SQLLikeRepository implements LikeRepositoryInterface
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create(int $userId, int $imageId): bool
    {
        $now = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare('INSERT INTO likes (user_id, image_id, created_at)
            VALUES (:user_id, :image_id, :created_at)');

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':image_id', $imageId);
        $stmt->bindParam(':created_at', $now);
        $stmt->execute();

        return ($stmt->rowCount() > 0);
    }

    public function delete(int $userId, int $imageId): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM likes WHERE user_id = :user_id AND image_id = :image_id');

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':image_id', $imageId);
        $stmt->execute();

        return ($stmt->rowCount() > 0);
    }

    public function exists(int $userId, int $imageId): bool
    {
        $stmt = $this->conn->prepare('SELECT 1 FROM likes WHERE user_id = :user_id AND image_id = :image_id LIMIT 1');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':image_id', $imageId);
        $stmt->execute();

        return (bool)$stmt->fetchColumn();
    }
}
