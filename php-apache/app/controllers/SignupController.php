<?php

namespace controllers;

use http\Response;
use models\User;
use repositories\SQLUserRepository;
use database\Sqlite;

class SignupController
{
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
        $user = new User();
        $user->setEmail($_POST['email']);
        $user->setUsername($_POST['username']);
        $user->setPassword($_POST['password']);

        $db = new Sqlite(dirname(__DIR__) . '/database/SQLite/camagru.db');
        $repository = new SQLUserRepository($db->getConnection());

        if (!$user->validate($repository)) {
            $_SESSION['errors'] = $user->getErrors();

            $_SESSION['old'] = [
                'email' => $user->getEmail(),
                'username' => $user->getUsername()
            ];

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/signup');
            $response->send();
            exit;
        }

        $user->setHashedPassword($_POST['password']);
        $repository->save($user);

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/login');
        $response->send();
        exit;
    }
}
