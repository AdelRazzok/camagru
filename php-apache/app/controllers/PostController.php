<?php

namespace controllers;

use core\ApiController;
use http\Response;
use http\SessionManager;
use database\Postgresql;
use repositories\SQLImageRepository;

class PostController extends ApiController
{
    private SQLImageRepository $imageRepository;
    private SessionManager $session;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $this->imageRepository = new SQLImageRepository($db->getConnection());
        $this->session = SessionManager::getInstance();
    }

    public function delete(int $imageId): void
    {
        $image = $this->imageRepository->findById($imageId);
        if (!$image) {
            $this->jsonError('Image not found.', Response::HTTP_NOT_FOUND);
        }

        $currentUser = $this->session->get('user');
        if ($currentUser->getId() !== $image->getUserId()) {
            $this->jsonError('Unauthorized action.', Response::HTTP_FORBIDDEN);
        }

        if ($this->imageRepository->delete($imageId)) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ], Response::HTTP_OK);
        } else {
            $this->jsonError('Failed to delete image.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
