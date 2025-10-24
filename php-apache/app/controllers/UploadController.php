<?php

namespace controllers;

use models\Image;
use services\UploadService;
use http\SessionManager;

class UploadController
{
    private UploadService $uploadService;
    private SessionManager $session;

    public function __construct()
    {
        $this->uploadService = new UploadService();
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

        // echo '<pre>', var_dump($result), '</pre>';

        echo json_encode($result);
    }
}
