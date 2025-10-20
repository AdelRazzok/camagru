<?php

namespace controllers;

use database\Postgresql;
use http\SessionManager;
use http\Response;
use models\enums\TokenType;
use repositories\SQLTokenRepository;
use repositories\SQLUserRepository;
use services\EmailService;
use services\TokenService;
use services\UserService;

class VerifyController
{
    private TokenService $tokenService;
    private UserService $userService;
    private EmailService $emailService;
    private SessionManager $session;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $tokenRepository = new SQLTokenRepository($db->getConnection());
        $userRepository = new SQLUserRepository($db->getConnection());
        $this->tokenService = new TokenService($tokenRepository);
        $this->userService = new UserService($userRepository);
        $this->emailService = new EmailService();
        $this->session = SessionManager::getInstance();
    }

    public function verifyAccount()
    {
        if (!isset($_GET['token']) || empty($_GET['token'])) {
            $error = 'Token is required for account verification.';
            $isExpired = false;

            require_once dirname(__DIR__) . '/views/verify/error.php';
            exit;
        }
        $token = $_GET['token'];

        $verifyTokenResult = $this->tokenService->verifyToken($token, TokenType::EmailVerification);

        $alreadyVerified = $verifyTokenResult['message'] === 'Token already used.';

        if (!$verifyTokenResult['success'] && !$alreadyVerified) {
            $error = $verifyTokenResult['user_friendly_message'] ?? 'Invalid verification link.';
            $isExpired = $verifyTokenResult['message'] === 'Token expired.';

            require_once dirname(__DIR__) . '/views/verify/error.php';
            exit;
        }

        if (!$alreadyVerified) {
            $this->userService->verifyUserEmail($verifyTokenResult['userId']);
        }
        $this->tokenService->invalidateToken($token, TokenType::EmailVerification);

        require_once dirname(__DIR__) . '/views/verify/success.php';
    }

    public function showResendVerificationForm()
    {
        $title = 'Camagru - Resend Verification';
        $error = $this->session->getFlash('error', '');

        require_once dirname(__DIR__) . '/views/verify/resend_form.php';
    }

    public function resendVerification()
    {
        $email = $_POST['email'] ?? '';

        $userResult = $this->userService->findByEmailOrFail($email);
        $userNotFound = $userResult['success'] === false && $userResult['message'] === 'User not found.';

        $this->session->flash('success', 'If your account exists, you will receive a verification email shortly.');

        if ($userNotFound) {
            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/login');
            $response->send();
            exit;
        }

        if (!$userResult['success']) {
            $this->session->flash('error', $userResult['message']);

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/resend-verification');
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
        }

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/login');
        $response->send();
    }
}
