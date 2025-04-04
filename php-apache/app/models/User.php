<?php

namespace models;

use core\Model;

class User extends Model
{
    protected int $id;
    protected string $email;
    protected string $username;
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

    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    public function setPassword(string $password): void
    {
        unset($this->errors['password']);

        if (empty($password)) {
            $error['password'] = 'Password is required.';
            return;
        } else if (strlen($password) < 8) {
            $error['password'] = 'Password must be at least 8 characters long.';
            return;
        }

        $this->hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->hashed_password);
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

    public function validate()
    {
        $this->errors = [];

        if (empty($this->email)) {
            $this->errors['email'] = 'Email is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format.';
        } elseif (strlen($this->email) > 254) {
            $this->errors['email'] = 'Email is too long.';
        }

        if (empty($this->username)) {
            $this->errors['username'] = 'Username is required.';
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 20) {
            $this->errors['username'] = 'Username must be between 3 and 20 characters long.';
        } else if (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->username)) {
            $this->errors['username'] = 'Username can only contain letters, numbers, underscores and hyphens.';
        }

        return empty($this->errors);
    }
}
