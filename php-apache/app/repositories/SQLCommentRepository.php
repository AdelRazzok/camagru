<?php

namespace repositories;

use PDO;
use models\Comment;
use repositories\interfaces\CommentRepositoryInterface;

class SQLCommentRepository implements CommentRepositoryInterface
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findByImageId(int $imageId): array
    {
        $stmt = $this->conn->prepare(
            'SELECT c.*, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.image_id = :image_id
            ORDER BY c.created_at ASC'
        );
        $stmt->bindValue(':image_id', $imageId, PDO::PARAM_INT);
        $stmt->execute();
        $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($commentsData as $commentData) {
            $comments[] = $this->mapToComment($commentData);
        }
        return $comments;
    }

    public function findById(int $id): ?Comment
    {
        $stmt = $this->conn->prepare('SELECT * FROM comments WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $commentData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $commentData ? $this->mapToComment($commentData) : null;
    }

    public function save(Comment $comment): void
    {
        $now = date('Y-m-d H:i:s');

        if ($comment->getId()) {
            $stmt = $this->conn->prepare(
                'UPDATE comments
                    SET content = :content, updated_at = :updated_at
                    WHERE id = :id'
            );
            $stmt->bindValue(':id', $comment->getId(), PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare(
                'INSERT INTO comments (image_id, user_id, content, created_at, updated_at)
                    VALUES (:image_id, :user_id, :content, :created_at, :updated_at)'
            );
            $stmt->bindValue(':image_id', $comment->getImageId(), PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $comment->getUserId(), PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $now);
        }

        $stmt->bindValue(':content', $comment->getContent());
        $stmt->bindValue(':updated_at', $now);
        $stmt->execute();

        if (!$comment->getId()) {
            $comment->setId((int)$this->conn->lastInsertId());
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function mapToComment(array $data): Comment
    {
        $comment = (new Comment())
            ->setId($data['id'])
            ->setImageId($data['image_id'])
            ->setUserId($data['user_id'])
            ->setContent($data['content'])
            ->setUsername($data['username'] ?? null);

        return $comment;
    }
}
