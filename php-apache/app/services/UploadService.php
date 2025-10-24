<?php

namespace services;

use models\Image;

class UploadService
{
    private const UPLOAD_DIR = '/var/www/html/uploads/';
    private const STICKER_DIR = '/var/www/html/public/images/stickers/';
    private const CANVAS_WIDTH = 800;
    private const CANVAS_HEIGHT = 600;

    public function mergeImageWithSticker(array $file, int $sticker_id, int $user_id): array
    {
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
        
        $imageFile = $this->loadImage($file['tmp_name']);
        if (!$imageFile) {
            return [
                'success' => false,
                'errors' => ['Failed to load uploaded image.']
            ];
        }

        $canvas = imagecreatetruecolor(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);
        if (!$canvas) {
            return [
                'success' => false,
                'errors' => ['Failed to create canvas.']
            ];
        }

        imagecopyresampled(
            $canvas, $imageFile,
            0, 0, 0, 0,
            self::CANVAS_WIDTH, self::CANVAS_HEIGHT,
            imagesx($imageFile), imagesy($imageFile)
        );

        $stickerPath = self::STICKER_DIR . 'sticker_' . ($sticker_id + 1) . '.png';
        if (!file_exists($stickerPath)) {
            return [
                'success' => false,
                'errors' => ['Sticker file not found.']
            ];
        }

        $sticker = imagecreatefrompng($stickerPath);
        if (!$sticker) {
            return [
                'success' => false,
                'errors' => ['Fail to load sticker image.']
            ];
        }

        $stickerWidth = imagesx($sticker);
        $stickerHeight = imagesy($sticker);
        $centerX = (int)((self::CANVAS_WIDTH - $stickerWidth) / 2);
        $centerY = (int)((self::CANVAS_HEIGHT - $stickerHeight) / 2);

        imagecopy(
            $canvas, $sticker,
            $centerX, $centerY,
            0, 0,
            $stickerWidth, $stickerHeight
        );

        $uploadPath = $this->generateUploadPath($user_id);
        $filename = $this->generateFilename();
        $filepath = $uploadPath . $filename;

        if (!imagepng($canvas, $filepath)) {
            return [
                'success' => false,
                'errors' => ['Failed to save merged image.']
            ];
        }

        $image->setFilePath($filepath);

        imagedestroy($imageFile);
        imagedestroy($sticker);
        imagedestroy($canvas);

        return [
            'success' => true,
            'image_path' => $image->getFilePath(),
            'errors' => []
        ];
    }

    private function loadImage(string $tmp_name)
    {
        if (!file_exists($tmp_name)) {
            return null;
        }

        $mime = mime_content_type($tmp_name);

        return match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($tmp_name),
            'image/png' => imagecreatefrompng($tmp_name),
            'image/gif' => imagecreatefromgif($tmp_name),
            'image/webp' => imagecreatefromwebp($tmp_name),
            default => null,
        };
    }

    private function generateUploadPath(int $user_id): string
    {
        $date = new \DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $uploadPath = self::UPLOAD_DIR . "{$year}/{$month}/{$day}/{$user_id}/";
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        return $uploadPath;
    }

    private function generateFilename(): string
    {
        $timestamp = time();
        $random = uniqid();
        $extension = 'png';

        return "camagru_{$timestamp}_{$random}.{$extension}";
    }
}
