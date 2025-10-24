<?php

namespace models;

use core\Model;
use finfo;

class Image extends Model
{
    protected ?int $id;
    protected string $file_path;
    protected string $extension;
    protected string $mime_type;
    protected string $original_name;
    protected int $file_size;

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    public function __construct()
    {
        parent::__construct();
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Image
    {
        $this->id = $id;
        return $this;
    }

    public function getFilePath(): string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): Image
    {
        $this->file_path = $file_path;
        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): Image
    {
        $this->extension = $extension;
        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    public function setMimeType(string $mime_type): Image
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    public function getOriginalName(): string
    {
        return $this->original_name;
    }

    public function setOriginalName(string $original_name): Image
    {
        $this->original_name = $original_name;
        return $this;
    }

    public function getFileSize(): int
    {
        return $this->file_size;
    }

    public function setFileSize(int $file_size): Image
    {
        $this->file_size = $file_size;
        return $this;
    }

    public function validate(): bool
    {
        $this->errors = [];

        if (!in_array(strtolower($this->extension), self::ALLOWED_EXTENSIONS)) {
            $this->errors['extension'] = 'Invalid file extension.';
        }

        if (!in_array($this->mime_type, self::ALLOWED_MIME_TYPES)) {
            $this->errors['mime_type'] = 'Invalid file type.';
        }

        if ($this->file_size > self::MAX_FILE_SIZE) {
            $this->errors['file_size'] = 'File size exceeds the maximum limit.';
        }
        return empty($this->errors);
    }

    public function isRealImage(string $tmp_name)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            $this->errors['real_image'] = 'File is not a valid image.';
            return false;
        }

        if (@getimagesize($tmp_name) === false) {
            $this->errors['real_image'] = 'Cannot read image data.';
            return false;
        }
        return true;
    }
}
