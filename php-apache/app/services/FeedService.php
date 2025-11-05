<?php

namespace services;

use http\SessionManager;
use repositories\interfaces\ImageRepositoryInterface;
use repositories\interfaces\UserRepositoryInterface;

class FeedService
{
    private ImageRepositoryInterface $imageRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->imageRepository = $imageRepository;
        $this->userRepository = $userRepository;
    }

    public function getFeed(): array
    {
        $feed = [];
        $images = $this->imageRepository->findAll();

        $session = SessionManager::getInstance();
        $currentUserId = $session->has('user') ? $session->get('user')->getId() : null;

        $imageIds = array_map(fn($img) => $img->getId(), $images);
        $likeCounts = $this->imageRepository->getLikeCounts($imageIds) ?? [];

        $likedIds = [];
        if ($currentUserId !== null) {
            $liked = $this->userRepository->getLikedImageIdsByUser($currentUserId);
            $likedIds = array_flip($liked);
        }

        foreach ($images as $image) {
            $id = $image->getId();
            $user = $this->userRepository->findById($image->getUserId());

            $feed[] = [
                'image' => $image,
                'file_path' => $image->getFilePath(),
                'author' => $user->getUsername(),
                'like_count' => $likeCounts[$id] ?? 0,
                'comment_count' => 0,
                'user_liked' => isset($likedIds[$id]),
                'created_at' => $image->getCreatedAt()->format('d/m/Y - H:i'),
            ];
        }
        return $feed;
    }
}
