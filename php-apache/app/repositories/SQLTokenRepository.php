<?php

namespace repositories;

use InvalidArgumentException;
use models\Token;
use models\enums\TokenType;
use repositories\interfaces\TokenRepositoryInterface;
use PDO;

class SQLTokenRepository implements TokenRepositoryInterface
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findById(int $id): ?Token
    {
        $stmt = $this->conn->prepare('SELECT * FROM tokens WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $tokenData ? $this->mapToToken($tokenData) : null;
    }

    public function findByToken(string $token, TokenType $type): ?Token
    {
        $stmt = $this->conn->prepare('SELECT * FROM tokens WHERE token = :token AND type = :type');
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':type', $type->value);
        $stmt->execute();
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $tokenData ? $this->mapToToken($tokenData) : null;
    }

    public function findByUserId(int $userId, TokenType $type): ?Token
    {
        $stmt = $this->conn->prepare('SELECT * FROM tokens WHERE user_id = :user_id AND type = :type');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type->value);
        $stmt->execute();
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $tokenData ? $this->mapToToken($tokenData) : null;
    }

    public function save(Token $token): void
    {
        if (!($token instanceof Token)) {
            throw new InvalidArgumentException('Expected instance of Token.');
        }

        $now = date('Y-m-d H:i:s');
        $expiresAt = $token->getExpiresAt()->format('Y-m-d H:i:s');

        if ($token->getId()) {
            $stmt = $this->conn->prepare(
                'UPDATE tokens
                    SET user_id = :user_id,
                        token = :token,
                        type = :type,
                        expires_at = :expires_at,
                        updated_at = :updated_at
                    WHERE id = :id'
            );
            $stmt->bindValue(':id', $token->getId(), PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare(
                'INSERT INTO tokens (user_id, token, type, expires_at, updated_at)
                    VALUES (:user_id, :token, :type, :expires_at, :created_at, :updated_at)'
            );
            $stmt->bindValue(':created_at', $now);
        }

        $stmt->bindValue(':user_id', $token->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':token', $token->getToken());
        $stmt->bindValue(':type', $token->getType());
        $stmt->bindValue(':expires_at', $expiresAt);
        $stmt->bindValue(':updated_at', $now);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM tokens WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteByUserId(int $userId, string $type): void
    {
        $stmt = $this->conn->prepare('DELETE FROM tokens WHERE user_id = :user_id AND type = :type');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type);
        $stmt->execute();
    }

    private function mapToToken(array $tokenData): Token
    {
        $token = (new Token())
            ->setId($tokenData['id'])
            ->setUserId($tokenData['user_id'])
            ->setToken($tokenData['token'])
            ->setType($tokenData['type'])
            ->setExpiresAt($tokenData['expires_at']);
        return $token;
    }
}
