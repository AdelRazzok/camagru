<?php

namespace controllers;

use http\Response;
use database\Postgresql;
use repositories\SQLLikeRepository;
use repositories\SQLImageRepository;
use http\SessionManager;

class LikeController
{
    private SQLLikeRepository $likeRepository;
    private SQLImageRepository $imageRepository;
    private SessionManager $session;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $this->likeRepository = new SQLLikeRepository($db->getConnection());
        $this->imageRepository = new SQLImageRepository($db->getConnection());
        $this->session = SessionManager::getInstance();
    }

    public function toggleLike(int $imageId): void
    {
        if ($imageId === null || $imageId <= 0) {
            $this->jsonError('Invalid image ID.', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->session->get('user');
        $userId = $user->getId();

        $image = $this->imageRepository->findById($imageId);
        if ($image === null) {
            $this->jsonError('Image not found.', Response::HTTP_NOT_FOUND);
        }

        if ($this->likeRepository->exists($userId, $imageId)) {
            $deleted = $this->likeRepository->delete($userId, $imageId);
            if (!$deleted) {
                $this->jsonError('Failed to remove like.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $liked = false;
            $status = Response::HTTP_OK;
        } else {
            $created = $this->likeRepository->create($userId, $imageId);
            if (!$created) {
                $this->jsonError('Failed to create like.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $liked = true;
            $status = Response::HTTP_CREATED;
        }

        $counts = $this->imageRepository->getLikeCounts([$imageId]);
        $count = $counts[$imageId] ?? 0;

        $this->jsonResponse(['liked' => $liked, 'count' => $count], $status);
    }

    private function jsonResponse(array $data, int $status = Response::HTTP_OK): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    private function jsonError(string $message, int $status): void
    {
        $this->jsonResponse(['error' => $message], $status);
    }
}
