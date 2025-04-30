<?php

namespace repositories;

use models\User;
use repositories\interfaces\UserRepositoryInterface;
use InvalidArgumentException;
use PDO;

class SQLUserRepository implements UserRepositoryInterface
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findAll(): array
    {
        $stmt = $this->conn->query('SELECT * FROM users');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($userData) {
            return $this->mapToUser($userData);
        }, $users);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ? $this->mapToUser($userData) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ? $this->mapToUser($userData) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ? $this->mapToUser($userData) : null;
    }

    public function save(User $user): User
    {
        if (!($user instanceof User)) {
            throw new InvalidArgumentException('Expected instance of User.');
        }

        $now = date('Y-m-d H:i:s');

        if ($user->getId()) {
            $stmt = $this->conn->prepare(
                'UPDATE users
                    SET email = :email,
                        username = :username,
                        password = :hashed_password,
                        email_verified = :email_verified,
                        email_notif_on_comment = :email_notif_on_comment,
                        updated_at = :updated_at
                    WHERE id = :id'
            );
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare(
                'INSERT INTO users (email, username, password, email_verified, email_notif_on_comment, created_at, updated_at)
                    VALUES (:email, :username, :hashed_password, :email_verified, :email_notif_on_comment, :created_at, :updated_at)'
            );
            $stmt->bindValue(':created_at', $now);
        }

        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':hashed_password', $user->getHashedPassword());
        $stmt->bindValue(':email_verified', $user->isEmailVerified(), PDO::PARAM_BOOL);
        $stmt->bindValue(':email_notif_on_comment', $user->isEmailNotifOnComment(), PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', $now);
        $stmt->execute();

        if (!$user->getId()) {
            $user->setId((int)$this->conn->lastInsertId());
        }
        return $user;
    }

    public function delete(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function mapToUser(array $userData): User
    {
        $user = (new User())
            ->setId($userData['id'])
            ->setEmail($userData['email'])
            ->setUsername($userData['username'])
            ->setHashedPassword($userData['password'])
            ->setEmailVerified($userData['email_verified'])
            ->setEmailNotifOnComment($userData['email_notif_on_comment']);
        return $user;
    }
}
