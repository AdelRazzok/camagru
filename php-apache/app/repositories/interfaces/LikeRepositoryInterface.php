<?php

namespace repositories\interfaces;

interface LikeRepositoryInterface
{
    public function create(int $userId, int $imageId): bool;
    public function delete(int $userId, int $imageId): bool;
    public function exists(int $userId, int $imageId): bool;
}
