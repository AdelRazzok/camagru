<?php

namespace services\interfaces;

interface UserServiceInterface
{
    public function createUser(string $email, string $username, string $password): array;
}
