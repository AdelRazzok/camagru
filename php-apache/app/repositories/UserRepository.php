<?php

use models\User;
use repositories\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    public function findAll(): array
    {
        return [];
    }

    public function findById(int $id): object
    {
        return new User();
    }

    public function findBy(array $criteria): array
    {
        return [];
    }

    public function save(object $entity): void
    {

    }

    public function delete(int $id): void
    {
        
    }
}
