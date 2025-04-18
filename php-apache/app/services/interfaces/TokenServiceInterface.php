<?php

namespace services\interfaces;

use models\enums\TokenType;

interface TokenServiceInterface
{
    public function generateToken(int $userId, TokenType $type, int $expiriesInHours = 24): string;
    public function verifyToken();
    public function deleteToken();
}
