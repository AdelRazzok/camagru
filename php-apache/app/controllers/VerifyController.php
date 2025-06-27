<?php

namespace controllers;

use database\Postgresql;
use http\SessionManager;
use http\Response;
use models\enums\TokenType;
use repositories\SQLTokenRepository;
use repositories\SQLUserRepository;
use services\TokenService;
use services\UserService;

class VerifyController
{
  private TokenService $tokenService;
  private UserService $userService;
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

    if (!$verifyTokenResult['success']) {
      $error = $verifyTokenResult['message'] === 'Token expired.' ? 'Your verification link has expired.' : 'Invalid verification link.';
      $isExpired = $verifyTokenResult['message'] === 'Token expired.';

      require_once dirname(__DIR__) . '/views/verify/error.php';
      exit;
    }

    $verifyUserEmailResult = $this->userService->verifyUserEmail($verifyTokenResult['userId']);
    $alreadyVerified = $verifyUserEmailResult['message'] === 'User email already verified.';

    $this->tokenService->invalidateToken($token, TokenType::EmailVerification);

    require_once dirname(__DIR__) . '/views/verify/success.php';
  }
}
