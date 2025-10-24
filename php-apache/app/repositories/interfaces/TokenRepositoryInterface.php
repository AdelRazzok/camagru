<?php

namespace repositories\interfaces;

use models\Token;
use models\enums\TokenType;

interface TokenRepositoryInterface
{
    public function findById(int $id): ?Token;
    public function findByToken(string $token, TokenType $type): ?Token;
    public function findByUserId(int $userId, TokenType $type): ?Token;
    public function save(Token $token): void;
    public function delete(int $id): void;
    public function deleteByUserId(int $userId, string $type): void;
}
