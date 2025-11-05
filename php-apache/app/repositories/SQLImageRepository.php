<?php

namespace repositories;

use PDO;
use models\Image;
use repositories\interfaces\ImageRepositoryInterface;
use database\Database;

class SQLImageRepository implements ImageRepositoryInterface
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findAll(): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM images ORDER BY created_at DESC');
        $stmt->execute();
        $imagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $images = [];
        foreach ($imagesData as $imageData) {
            $images[] = $this->mapToImage($imageData);
        }
        return $images;
    }

    public function findById(int $id): ?Image
    {
        $stmt = $this->conn->prepare('SELECT * FROM images WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $imageData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $imageData ? $this->mapToImage($imageData) : null;
    }

    public function findByUserId(int $user_id): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM images WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $imagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $images = [];
        foreach ($imagesData as $imageData) {
            $images[] = $this->mapToImage($imageData);
        }
        return $images;
    }

    public function save(Image $image): void
    {
        if (!($image instanceof Image)) {
            throw new \InvalidArgumentException('Expected instance of Image.');
        }

        $now = date('Y-m-d H:i:s');

        if ($image->getId()) {
            $stmt = $this->conn->prepare(
                'UPDATE images
                    SET user_id = :user_id,
                        file_path = :file_path,
                        extension = :extension,
                        mime_type = :mime_type,
                        original_name = :original_name,
                        file_size = :file_size,
                        updated_at = :updated_at
                WHERE id = :id
            '
            );
            $stmt->bindValue(':id', $image->getId(), PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare(
                'INSERT INTO images (user_id, file_path, extension, mime_type, original_name, file_size, created_at, updated_at)
                    VALUES (:user_id, :file_path, :extension, :mime_type, :original_name, :file_size, :created_at, :updated_at)'
            );
            $stmt->bindValue(':created_at', $now);
        }

        $stmt->bindValue(':user_id', $image->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':file_path', $image->getFilePath());
        $stmt->bindValue(':extension', $image->getExtension());
        $stmt->bindValue(':mime_type', $image->getMimeType());
        $stmt->bindValue(':original_name', $image->getOriginalName());
        $stmt->bindValue(':file_size', $image->getFileSize(), PDO::PARAM_INT);
        $stmt->bindValue(':updated_at', $now);
        $stmt->execute();

        if ($image->getId() === null) {
            $image->setId((int)$this->conn->lastInsertId());
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM images WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function mapToImage(array $data): Image
    {
        $image = (new Image())
            ->setId($data['id'])
            ->setUserId($data['user_id'])
            ->setFilePath($data['file_path'])
            ->setExtension($data['extension'])
            ->setMimeType($data['mime_type'])
            ->setOriginalName($data['original_name'])
            ->setFileSize($data['file_size']);
        return $image;
    }
}
