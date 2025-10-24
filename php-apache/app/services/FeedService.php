<?php

namespace services;

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
        $images = $this->imageRepository->findAll();
        $feed = [];

        foreach ($images as $image) {
            $user = $this->userRepository->findById($image->getUserId());

            $feed[] = [
                'image' => $image,
                'file_path' => $image->getFilePath(),
                'author' => $user->getUsername(),
                'like_count' => 0,
                'comment_count' => 0,
                'user_liked' => false,
                'created_at' => $image->getCreatedAt()->format('d/m/Y - H:i'),
            ];
        }

        return $feed;
    }
}
