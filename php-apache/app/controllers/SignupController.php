<?php

namespace controllers;

use http\Response;
use repositories\SQLUserRepository;
use database\Sqlite;
use services\UserService;

class SignupController
{
    private UserService $userService;

    public function __construct()
    {
        $db = new Sqlite(dirname(__DIR__) . '/database/SQLite/camagru.db');
        $repository = new SQLUserRepository($db->getConnection());
        $this->userService = new UserService($repository);
    }

    public function index()
    {
        $title = 'Camagru - Sign up';
        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? [];

        unset($_SESSION['errors'], $_SESSION['old']);

        require_once dirname(__DIR__) . '/views/signup/index.php';
    }

    public function store()
    {
        $result = $this->userService->createUser(
            $_POST['email'],
            $_POST['username'],
            $_POST['password']
        );

        if (!$result['success']) {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $result['data'];

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/signup');
            $response->send();
            exit;
        }

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/login');
        $response->send();
        exit;
    }
}
