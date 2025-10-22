<?php

namespace controllers;

use models\Image;
use services\UploadService;

class UploadController
{
    private UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    public function showUploadForm()
    {
        require_once dirname(__DIR__) . '/views/upload/index.php';
    }

    public function upload()
    {
        $this->uploadService->uploadImage($_FILES['image']);
    }
}
