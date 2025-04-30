<?php

namespace controllers;

use http\Response;
use database\Postgresql;
use database\Sqlite;
use models\enums\TokenType;
use repositories\SQLUserRepository;
use repositories\SQLTokenRepository;
use services\UserService;
use services\TokenService;
use services\EmailService;

class SignupController
{
    private UserService $userService;
    private TokenService $tokenService;
    private EmailService $emailService;

    public function __construct()
    {
        $db = new Sqlite(dirname(__DIR__) . '/database/SQLite/camagru.db');
        // $db = new Postgresql(
        //     getenv('POSTGRES_HOST'),
        //     (int)getenv('POSTGRES_PORT')
        // );

        $userRepository = new SQLUserRepository($db->getConnection());
        $tokenRepository = new SQLTokenRepository($db->getConnection());
        $this->userService = new UserService($userRepository);
        $this->tokenService = new TokenService($tokenRepository);
        $this->emailService = new EmailService();
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
            $this->emailService->sendVerification(
                $userResult['user']->getEmail(),
                $userResult['user']->getUsername(),
                $tokenResult['token']->getToken()
            );

            $_SESSION['success'] = 'Your account has been successfully created. Please check your inbox to activate your account.';
        }

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/login');
        $response->send();
        exit;
    }
}
