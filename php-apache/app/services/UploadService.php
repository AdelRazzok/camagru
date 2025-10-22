<?php

namespace services;

use models\Image;

class UploadService
{
    private const UPLOAD_DIR = '/var/www/html/uploads/';

    public function uploadImage(array $file)
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'image' => null,
                'errors' => 'File upload error.'
            ];
        }

        $image = (new Image())
            ->setExtension(pathinfo($file['name'], PATHINFO_EXTENSION))
            ->setMimeType($file['type'])
            ->setOriginalName($file['name'])
            ->setFileSize($file['size']);


        if (!$image->validate()) {
            return [
                'success' => false,
                'image' => $image,
                'errors' => $image->getErrors()
            ];
        }
        if (!$image->isRealImage($file['tmp_name'])) {
            return [
                'success' => false,
                'image' => $image,
                'errors' => $image->getErrors()
            ];
        }

        $filePath = $this->generateFilePath($image);
    }

    private function generateFilePath(Image $image): string
    {
        echo '<pre>', var_dump($image), '</pre>' ;
        return '';
    }
}
