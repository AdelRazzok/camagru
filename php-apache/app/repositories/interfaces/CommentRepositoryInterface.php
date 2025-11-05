<?php

namespace repositories\interfaces;

use models\Comment;

interface CommentRepositoryInterface
{
    public function findByImageId(int $imageId): array;
    public function findById(int $id): ?Comment;
    public function save(Comment $comment): void;
    public function delete(int $id): void;
}
