<?php

namespace controllers;

use http\Response;
use http\SessionManager;
use database\Postgresql;
use repositories\SQLUserRepository;
use repositories\SQLTokenRepository;
use services\UserService;
use services\TokenService;
use services\EmailService;
use models\enums\TokenType;

class PasswordResetController
{
  private SQLUserRepository $userRepository;
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

    $this->userRepository = new SQLUserRepository($db->getConnection());
    $tokenRepository = new SQLTokenRepository($db->getConnection());
    $this->userService = new UserService($this->userRepository);
    $this->tokenService = new TokenService($tokenRepository);
    $this->emailService = new EmailService();
    $this->session = SessionManager::getInstance();
  }

  public function showForm()
  {
    $title = 'Camagru - Forgot Password';
    $error = $this->session->getFlash('error', '');

    require_once dirname(__DIR__) . '/views/password_reset/send_form.php';
  }

  public function sendResetLink()
  {
    $email = $_POST['email'] ?? '';

    $userResult = $this->userService->findByEmailOrFail($email);
    $userNotFound = $userResult['success'] === false && $userResult['message'] === 'User not found.';

    $this->session->flash('success', 'If your account exists, you will receive a password reset email shortly.');

    if ($userNotFound) {
      $response = new Response(Response::HTTP_SEE_OTHER);
      $response->addHeader('Location', '/login');
      $response->send();
      exit;
    }

    if (!$userResult['success']) {
      $this->session->flash('error', $userResult['message']);

      $response = new Response(Response::HTTP_SEE_OTHER);
      $response->addHeader('Location', '/forgot-password');
      $response->send();
      exit;
    }

    $tokenResult = $this->tokenService->generateToken(
      $userResult['user']->getId(),
      TokenType::PasswordReset
    );

    if ($tokenResult) {
      $this->emailService->sendPasswordResetLink(
        $userResult['user']->getEmail(),
        $userResult['user']->getUsername(),
        $tokenResult['token']->getToken()
      );
    }

    $response = new Response(Response::HTTP_SEE_OTHER);
    $response->addHeader('Location', '/login');
    $response->send();
  }

  public function showResetForm()
  {
    if (!isset($_GET['token']) || empty($_GET['token'])) {
      $error = 'Token is required for password reset.';
      $isExpired = false;

      require_once dirname(__DIR__) . '/views/password_reset/error.php';
      exit;
    }
    $token = $_GET['token'];

    $verifyTokenResult = $this->tokenService->verifyToken($token, TokenType::PasswordReset);

    if (!$verifyTokenResult['success']) {
      $error = $verifyTokenResult['message'] === 'Token expired.' ? 'Your verification link has expired.' : 'Invalid verification link.';
      $isExpired = $verifyTokenResult['message'] === 'Token expired.';

      require_once dirname(__DIR__) . '/views/password_reset/error.php';
      exit;
    }

    $user = $this->userRepository->findById($verifyTokenResult['userId']);
    if (!$user) {
      $error = 'User not found.';
      $isExpired = false;

      require_once dirname(__DIR__) . '/views/password_reset/error.php';
      exit;
    }

    $title = 'Camagru - Reset Password';
    $error = $this->session->getFlash('error', '');
    $userId = $user->getId();

    require_once dirname(__DIR__) . '/views/password_reset/reset_form.php';
  }

  public function resetPassword()
  {
    $password = $_POST['password'] ?? '';
    $passwordConfirmation = $_POST['password_confirmation'] ?? '';
    $token = $_POST['token'] ?? '';
    $userId = (int)$_POST['user_id'] ?? '';

    $resetPasswordResult = $this->userService->resetPassword($userId, $password, $passwordConfirmation);

    if ($resetPasswordResult['success']) {
      $this->session->flash('success', $resetPasswordResult['message']);

      $response = new Response(Response::HTTP_SEE_OTHER);
      $response->addHeader('Location', '/login');
      $response->send();
      exit;
    } else {
      $this->session->flash('error', $resetPasswordResult['message']);

      $response = new Response(Response::HTTP_SEE_OTHER);
      $response->addHeader('Location', '/reset-password?token=' . $token);
      $response->send();
      exit;
    }
  }
}
