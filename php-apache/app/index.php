<?php

require_once __DIR__ . '/autoload.php';

use http\Router;
use http\middlewares\AuthMiddleware;
use http\middlewares\GuestMiddleware;
use http\middlewares\ApiAuthMiddleware;
use http\SessionManager;
use controllers\HomeController;
use controllers\LikeController;
use controllers\LoginController;
use controllers\SignupController;
use controllers\VerifyController;
use controllers\PasswordResetController;
use controllers\UploadController;
use controllers\ProfileController;

date_default_timezone_set('Europe/Paris');

$session = SessionManager::getInstance();
$session->start();

$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [LoginController::class, 'index'], [
    'middlewares' => [GuestMiddleware::class]
]);
$router->post('/login', [LoginController::class, 'authenticate'], [
    'middlewares' => [GuestMiddleware::class]
]);

$router->get('/signup', [SignupController::class, 'index'], [
    'middlewares' => [GuestMiddleware::class]
]);
$router->post('/signup', [SignupController::class, 'store'], [
    'middlewares' => [GuestMiddleware::class]
]);

$router->get('/verify-account', [VerifyController::class, 'verifyAccount']);
$router->get('/resend-verification', [VerifyController::class, 'showResendVerificationForm']);
$router->post('/resend-verification', [VerifyController::class, 'resendVerification']);

$router->get('/forgot-password', [PasswordResetController::class, 'showForm']);
$router->post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
$router->get('/reset-password', [PasswordResetController::class, 'showResetForm']);
$router->post('/reset-password', [PasswordResetController::class, 'resetPassword']);

$router->get('/upload', [UploadController::class, 'showUploadForm'], [
    'middlewares' => [AuthMiddleware::class]
]);
$router->post('/upload', [UploadController::class, 'upload'], [
    'middlewares' => [AuthMiddleware::class]
]);

$router->get('/profile', [ProfileController::class, 'index'], [
    'middlewares' => [AuthMiddleware::class]
]);
$router->post('/profile', [ProfileController::class, 'update'], [
    'middlewares' => [AuthMiddleware::class]
]);

$router->post('/image/{id}/like', [LikeController::class, 'toggleLike'], [
    'middlewares' => [ApiAuthMiddleware::class]
]);

$router->get('/logout', [LoginController::class, 'logout']);

$router->run();
