<?php

namespace controllers;

use http\Response;
use http\Request;
use models\User;
use repositories\SQLUserRepository;
use database\SQLite\Sqlite;

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

        if (!$user->validate()) {
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

        // $repository = new SQLUserRepository(new Sqlite('camagru.db'));
        // $repository->save($user);

        // $response = new Response(Response::HTTP_CREATED);
        // $response->addHeader('Location', '/login');
        // $response->send();
    }
}
