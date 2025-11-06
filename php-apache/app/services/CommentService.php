<?php

namespace services;

use models\Comment;
use repositories\interfaces\CommentRepositoryInterface;
use repositories\interfaces\UserRepositoryInterface;
use repositories\SQLUserRepository;
use services\EmailService;

class CommentService
{
    private CommentRepositoryInterface $commentRepository;
    private UserRepositoryInterface $userRepository;
    private EmailService $emailService;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
        $this->emailService = new EmailService();
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

        $user = $this->userRepository->findById($userId);

        if ($user->isEmailNotifOnComment()) {
            $this->emailService->sendCommentNotification(
                $user->getEmail(),
                $user->getUsername(),
                $content
            );
        }

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
