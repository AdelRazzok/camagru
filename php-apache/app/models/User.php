<?php

namespace models;

use core\Model;
use repositories\interfaces\UserRepositoryInterface;

class User extends Model
{
    protected ?int $id;
    protected string $email;
    protected string $username;
    protected string $password;
    protected string $hashed_password;
    protected bool $email_verified;
    protected bool $email_notif_on_comment;

    public function __construct()
    {
        parent::__construct();
        $this->id = null;
        $this->email_verified = false;
        $this->email_notif_on_comment = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    public function setHashedPassword(string $hashed_password): User
    {
        $this->hashed_password = $hashed_password;
        return $this;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->hashed_password);
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified;
    }

    public function setEmailVerified(bool $email_verified): User
    {
        $this->email_verified = $email_verified;
        return $this;
    }

    public function isEmailNotifOnComment(): bool
    {
        return $this->email_notif_on_comment;
    }

    public function setEmailNotifOnComment(bool $email_notif_on_comment): User
    {
        $this->email_notif_on_comment = $email_notif_on_comment;
        return $this;
    }

    public function validate(UserRepositoryInterface $repository): bool
    {
        $this->errors = [];

        if (empty($this->email)) {
            $this->errors['email'] = 'Email is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format.';
        } elseif (strlen($this->email) > 254) {
            $this->errors['email'] = 'Email is too long.';
        } else if ($repository->findByEmail($this->email)) {
            $this->errors['email'] = 'You can\'t use this email address.';
        }

        if (empty($this->username)) {
            $this->errors['username'] = 'Username is required.';
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 20) {
            $this->errors['username'] = 'Username must be between 3 and 20 characters long.';
        } else if (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->username)) {
            $this->errors['username'] = 'Username can only contain letters, numbers, underscores and hyphens.';
        } else if ($repository->findByUsername($this->username)) {
            $this->errors['username'] = 'Username is already taken.';
        }

        if (empty($this->password)) {
            $this->errors['password'] = 'Password is required.';
        } else if (strlen($this->password) < 8) {
            $this->errors['password'] = 'Password must be at least 8 characters long.';
        }

        return empty($this->errors);
    }
}
