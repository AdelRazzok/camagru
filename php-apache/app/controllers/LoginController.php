<?php

namespace controllers;

use http\Response;
use database\Postgresql;
use database\Sqlite;
use repositories\SQLUserRepository;
use services\UserService;

class LoginController
{
    private UserService $userService;

    public function __construct()
    {
        $db = new Sqlite(dirname(__DIR__) . '/database/SQLite/camagru.db');
        // $db = new Postgresql(
        //     getenv('POSTGRES_HOST'),
        //     (int)getenv('POSTGRES_PORT')
        // );

        $userRepository = new SQLUserRepository($db->getConnection());
        $this->userService = new UserService($userRepository);
    }

    public function index()
    {
        $title = 'Camagru - Login';
        require_once dirname(__DIR__) . '/views/login/index.php';
        unset($_SESSION['success']);
    }

    public function authenticate()
    {
        $result = $this->userService->authenticateUser($_POST['username'], $_POST['password']);

        if (!$result['success']) {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = [
                'username' => $_POST['username']
            ];

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/login');
            $response->send();
            exit;
        }

        $_SESSION['user'] = $result['user'];

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/');
        $response->send();
        exit;
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/');
        $response->send();
        exit;
    }
}
