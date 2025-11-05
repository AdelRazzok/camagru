<?php

namespace controllers;

use http\Response;
use core\ApiController;
use http\SessionManager;
use database\Postgresql;
use repositories\SQLCommentRepository;
use repositories\SQLImageRepository;
use services\CommentService;

class CommentController extends ApiController
{
    private CommentService $commentService;
    private SQLImageRepository $imageRepository;
    private SessionManager $session;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $commentRepository = new SQLCommentRepository($db->getConnection());
        $this->commentService = new CommentService($commentRepository);
        $this->imageRepository = new SQLImageRepository($db->getConnection());
        $this->session = SessionManager::getInstance();
    }

    public function store(int $imageId): void
    {
        $image = $this->imageRepository->findById($imageId);
        if (!$image) {
            $this->jsonError('Image not found.', Response::HTTP_NOT_FOUND);
        }

        $user = $this->session->get('user');
        $content = $_POST['content'] ?? '';

        $result = $this->commentService->createComment($imageId, $user->getId(), $content);

        if (!$result['success']) {
            $this->jsonError('Failed to create comment.', Response::HTTP_BAD_REQUEST);
        }

        $this->jsonResponse([
            'success' => true,
            'comment' => [
                'id' => $result['comment']->getId(),
                'content' => htmlspecialchars($result['comment']->getContent()),
                'author' => $user->getUsername(),
                'created_at' => date('d/m/Y - H:i')
            ]
        ], Response::HTTP_CREATED);
    }

    public function listComments(int $imageId): void
    {
        $image = $this->imageRepository->findById($imageId);
        if (!$image) {
            $this->jsonError('Image not found.', Response::HTTP_NOT_FOUND);
        }

        $comments = $this->commentService->getCommentsByImageId($imageId);

        $formatedComments = array_map(function ($comment) {
            return [
                'id' => $comment->getId(),
                'content' => htmlspecialchars($comment->getContent()),
                'author' => htmlspecialchars($comment->getUsername()),
                'created_at' => $comment->getCreatedAt()->format('d/m/Y - H:i')
            ];
        }, $comments);

        $this->jsonResponse(['comments' => $formatedComments], Response::HTTP_OK);
    }
}
