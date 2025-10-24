<?php

namespace controllers;

use database\Postgresql;
use http\SessionManager;
use models\Image;
use repositories\SQLImageRepository;
use services\UploadService;

class UploadController
{
    private UploadService $uploadService;
    private SessionManager $session;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $imageRepository = new SQLImageRepository($db->getConnection());
        $this->uploadService = new UploadService($imageRepository);
        $this->session = SessionManager::getInstance();
    }

    public function showUploadForm()
    {
        require_once dirname(__DIR__) . '/views/upload/index.php';
    }

    public function upload()
    {
        $currentUserId = $this->session->get('user')->getId();

        $result = $this->uploadService->mergeImageWithSticker($_FILES['image'], $_POST['sticker_id'], $currentUserId);

        echo json_encode($result);
    }
}
