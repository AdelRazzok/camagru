<?php

namespace services;

use http\SessionManager;
use repositories\interfaces\ImageRepositoryInterface;
use repositories\interfaces\UserRepositoryInterface;

class FeedService
{
    private ImageRepositoryInterface $imageRepository;
    private UserRepositoryInterface $userRepository;
    private const POSTS_PER_PAGE = 5;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->imageRepository = $imageRepository;
        $this->userRepository = $userRepository;
    }

    public function getFeed(int $page = 1): array
    {
        $feed = [];
        $offset = ($page - 1) * self::POSTS_PER_PAGE;

        $images = $this->imageRepository->findAll($offset, self::POSTS_PER_PAGE);
        $totalImages = $this->imageRepository->countAll();

        $session = SessionManager::getInstance();
        $currentUserId = $session->has('user') ? $session->get('user')->getId() : null;

        $imageIds = array_map(fn($img) => $img->getId(), $images);
        $likeCounts = $this->imageRepository->getLikeCounts($imageIds) ?? [];
        $commentCounts = $this->imageRepository->getCommentCounts($imageIds) ?? [];

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
                'comment_count' => $commentCounts[$id] ?? 0,
                'user_liked' => isset($likedIds[$id]),
                'user_is_owner' => $currentUserId === $image->getUserId(),
                'created_at' => $image->getCreatedAt()->format('d/m/Y - H:i'),
            ];
        }

        return [
            'posts' => $feed,
            'current_page' => $page,
            'total_pages' => ceil($totalImages / self::POSTS_PER_PAGE),
            'total_posts' => $totalImages,
        ];
    }
}
