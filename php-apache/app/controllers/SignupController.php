<?php

namespace controllers;

use http\Response;
use database\Postgresql;
use models\enums\TokenType;
use repositories\SQLUserRepository;
use repositories\SQLTokenRepository;
use services\UserService;
use services\TokenService;

class SignupController
{
    private UserService $userService;
    private TokenService $tokenService;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );
        $userRepository = new SQLUserRepository($db->getConnection());
        $tokenRepository = new SQLTokenRepository($db->getConnection());
        $this->userService = new UserService($userRepository);
        $this->tokenService = new TokenService($tokenRepository);
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
        $userResult = $this->userService->createUser(
            $_POST['email'],
            $_POST['username'],
            $_POST['password']
        );

        if (!$userResult['success']) {
            $_SESSION['errors'] = $userResult['errors'];
            $_SESSION['old'] = $userResult['data'];

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/signup');
            $response->send();
            exit;
        }

        $tokenResult = $this->tokenService->generateToken(
            $userResult['user']->getId(),
            TokenType::EmailVerification
        );

        if ($tokenResult['success']) {
        }

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/login');
        $response->send();
        exit;
    }
}
