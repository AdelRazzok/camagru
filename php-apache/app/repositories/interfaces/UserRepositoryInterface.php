<?php

namespace repositories\interfaces;

use models\User;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?User;
    public function save(User $entity): void;
    public function delete(int $id): void;
}
