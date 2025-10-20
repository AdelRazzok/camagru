<?php

namespace services;

use repositories\interfaces\TokenRepositoryInterface;
use models\enums\TokenType;
use models\Token;
use DateTime;

class TokenService
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
            ->setIsUsed(false)
            ->setExpiresAt(new DateTime("+{$expiriesInHours} hours"));

        $this->tokenRepository->save($token);

        return [
            'success' => true,
            'token' => $token,
            'message' => 'Token generated successfully.'
        ];
    }

    public function verifyToken(string $token, TokenType $type): array
    {
        $tokenEntity = $this->tokenRepository->findByToken($token, $type);

        if (!$tokenEntity) {
            return [
                'success' => false,
                'message' => 'Token not found.',
                'user_friendly_message' => 'Invalid verification link.'
            ];
        }

        if ($tokenEntity->isUsed()) {
            return [
                'success' => false,
                'message' => 'Token already used.',
                'user_friendly_message' => 'This verification link has already been used.'
            ];
        }

        $now = new DateTime();
        if ($tokenEntity->getExpiresAt() < $now) {
            return [
                'success' => false,
                'message' => 'Token expired.',
                'user_friendly_message' => 'Your verification link has expired.'
            ];
        }

        return [
            'success' => true,
            'userId' => $tokenEntity->getUserId(),
            'token' => $tokenEntity,
            'message' => 'Token is valid.'
        ];
    }

    public function invalidateToken(string $token, TokenType $type): array
    {
        $tokenEntity = $this->tokenRepository->findByToken($token, $type);

        if (!$tokenEntity) {
            return ['success' => false, 'message' => 'Token not found.'];
        }

        $tokenEntity->setIsUsed(true);
        $this->tokenRepository->save($tokenEntity);

        return [
            'success' => true,
            'message' => 'Token invalidated successfully.'
        ];
    }
}
