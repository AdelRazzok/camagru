<?php

namespace controllers;

use models\Image;

class UploadController
{
    public function showUploadForm()
    {
        require_once dirname(__DIR__) . '/views/upload/index.php';
    }
    public function upload()
    {
        $image = (new Image())
            ->setExtension(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION))
            ->setMimeType($_FILES['image']['type'])
            ->setOriginalName($_FILES['image']['name'])
            ->setFileSize($_FILES['image']['size']);

        echo '<pre>', var_dump($image), '</pre>';

        $image->validate();

        $image->isRealImage($_FILES['image']['tmp_name']);

        echo '<pre>', var_dump($image->getErrors()), '</pre>';
    }
}
