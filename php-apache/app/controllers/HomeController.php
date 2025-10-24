<?php

namespace controllers;

use database\Postgresql;
use repositories\SQLImageRepository;
use repositories\SQLUserRepository;
use services\FeedService;

class HomeController
{
    private FeedService $feedService;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $imageRepository = new SQLImageRepository($db->getConnection());
        $userRepository = new SQLUserRepository($db->getConnection());
        $this->feedService = new FeedService($imageRepository, $userRepository);
    }

    public function index()
    {
        $images = $this->feedService->getFeed();
        $title = 'Camagru - Home';
        require_once dirname(__DIR__) . '/views/home/index.php';
    }
}
