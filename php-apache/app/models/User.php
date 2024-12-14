<?php

namespace models;

use core\Model;

class User extends Model
{
    protected int $id;
    protected string $email;
    protected string $username;
    protected string $password;
    protected string $hashed_password;
    protected bool $email_verified;
    protected bool $email_notif_on_comment;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    public function setHashedPassword(string $hashed_password): void
    {
        $this->hashed_password = $hashed_password;
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified;
    }

    public function setEmailVerified(bool $email_verified): void
    {
        $this->email_verified = $email_verified;
    }

    public function isEmailNotifOnComment(): bool
    {
        return $this->email_notif_on_comment;
    }

    public function setEmailNotifOnComment(bool $email_notif_on_comment): void
    {
        $this->email_notif_on_comment = $email_notif_on_comment;
    }
}
