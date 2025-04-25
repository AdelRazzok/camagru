<?php

namespace repositories\interfaces;

use models\User;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findByUsername(string $username): ?User;
    public function save(User $entity): User;
    public function delete(int $id): void;
}
