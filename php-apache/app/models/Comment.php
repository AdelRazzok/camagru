<?php

namespace models;

use core\Model;

class Comment extends Model
{
    protected ?int $id;
    protected int $image_id;
    protected int $user_id;
    protected string $content;
    public ?string $username = null;

    private const MAX_CONTENT_LENGTH = 200;

    public function __construct()
    {
        parent::__construct();
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Comment
    {
        $this->id = $id;
        return $this;
    }

    public function getImageId(): int
    {
        return $this->image_id;
    }

    public function setImageId(int $image_id): Comment
    {
        $this->image_id = $image_id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): Comment
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Comment
    {
        $this->content = $content;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): Comment
    {
        $this->username = $username;
        return $this;
    }

    public function validate(): bool
    {
        $this->errors = [];

        if (empty($this->content)) {
            $this->errors['content'] = 'Comment cannot be empty.';
        } elseif (strlen($this->content) > self::MAX_CONTENT_LENGTH) {
            $this->errors['content'] = 'Comment must not exceed ' . self::MAX_CONTENT_LENGTH . ' characters.';
        }
        return empty($this->errors);
    }
}
