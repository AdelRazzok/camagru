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
            'token' => $token,
            'message' => 'Account created successfully. Please check your inbox to activate it.'
        ];
    }

    public function verifyToken(string $token, TokenType $type): array
    {
        $tokenEntity = $this->tokenRepository->findByToken($token, $type);

        if (!$tokenEntity) {
            return ['success' => false, 'message' => 'Token not found.'];
        }

        $now = new DateTime();
        if ($tokenEntity->getExpiresAt() < $now) {
            return ['success' => false, 'message' => 'Token expired.'];
        }

        return [
            'success' => true,
            'userId' => $tokenEntity->getUserId(),
            'token' => $tokenEntity,
            'message' => 'Token is valid.'
        ];
    }

    public function invalidateToken(string $token, TokenType $type) {}
}
