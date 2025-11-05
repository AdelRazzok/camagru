<?php

namespace services;

use models\Comment;
use repositories\interfaces\CommentRepositoryInterface;

class CommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(int $imageId, int $userId, string $content): array
    {
        $comment = (new Comment())
            ->setImageId($imageId)
            ->setUserId($userId)
            ->setContent($content);

        if (!$comment->validate()) {
            return [
                'success' => false,
                'errors' => $comment->getErrors()
            ];
        }

        $this->commentRepository->save($comment);

        return [
            'success' => true,
            'comment' => $comment
        ];
    }

    public function getCommentsByImageId(int $imageId): array
    {
        return $this->commentRepository->findByImageId($imageId);
    }
}
