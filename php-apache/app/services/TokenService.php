<?php

namespace services;

use services\interfaces\TokenServiceInterface;
use repositories\interfaces\TokenRepositoryInterface;
use models\enums\TokenType;
use models\Token;
use DateTime;

class TokenService implements TokenServiceInterface
{
    private TokenRepositoryInterface $tokenRepository;

    public function __construct(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function generateToken(int $userId, TokenType $type, int $expiriesInHours = 24): array
    {
        $token = (new Token())
            ->setUserId($userId)
            ->setToken(bin2hex(random_bytes(32)))
            ->setType($type)
            ->setExpiresAt(new DateTime("+{$expiriesInHours} hours"));

        $this->tokenRepository->save($token);

        return [
            'success' => true,
            'token' => $token
        ];
    }

    public function verifyToken(int $userId, TokenType $type): bool
    {
        return true;
    }
}
