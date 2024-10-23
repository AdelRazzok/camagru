<?php

namespace app\repositories;

interface RepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): object;
    public function findBy(array $criteria): array;
    public function save(object $entity): void;
    public function delete(int $id): void;
}
