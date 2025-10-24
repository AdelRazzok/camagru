<?php

namespace controllers;

use http\Response;
use http\SessionManager;
use database\Postgresql;
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
    private SessionManager $session;

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
        $this->emailService = new EmailService();
        $this->session = SessionManager::getInstance();
    }

    public function index()
    {
        $title = 'Camagru - Sign up';
        $errors = $this->session->getFlash('errors', []);
        $old = $this->session->getFlash('old', []);

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
            $this->session->flash('errors', $userResult['errors']);
            $this->session->flash('old', $userResult['data']);

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

            $this->session->flash('success', 'Account created successfully! Please check your inbox to activate it.');
        }

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/login');
        $response->send();
        exit;
    }
}
