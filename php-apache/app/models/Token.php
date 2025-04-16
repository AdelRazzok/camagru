<?php

namespace models;

use core\Model;
use models\enums\TokenType;
use DateTime;

class Token extends Model
{
    protected ?int $id;
    protected int $user_id;
    protected string $token;
    protected TokenType $type;
    protected DateTime $expires_at;

    public function __construct()
    {
        parent::__construct();
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Token
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): Token
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): Token
    {
        $this->token = $token;
        return $this;
    }

    public function getType(): TokenType
    {
        return $this->type;
    }

    public function getTypeValue(): string
    {
        return $this->type->value;
    }

    public function setType(TokenType $type): Token
    {
        $this->type = $type;
        return $this;
    }

    public function getExpiresAt(): DateTime
    {
        return $this->expires_at;
    }

    public function setExpiresAt(DateTime $expires_at): Token
    {
        $this->expires_at = $expires_at;
        return $this;
    }
}
