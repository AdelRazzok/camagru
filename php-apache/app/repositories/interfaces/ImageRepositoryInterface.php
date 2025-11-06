<?php

namespace repositories\interfaces;

use models\Image;

interface ImageRepositoryInterface
{
    public function findAll(?int $offset = null, ?int $limit = null): array;
    public function findById(int $id): ?Image;
    public function findByUserId(int $user_id): array;
    public function getLikeCounts(array $imageIds): array;
    public function getCommentCounts(array $imageIds): array;
    public function countAll(): int;
    public function save(Image $image): void;
    public function delete(int $id): bool;
}
