<?php

namespace repositories\interfaces;

use models\Image;

interface ImageRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Image;
    public function findByUserId(int $user_id): array;
    public function save(Image $image): void;
    public function delete(int $id): void;
}
