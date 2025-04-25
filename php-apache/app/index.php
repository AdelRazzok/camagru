<?php

require_once __DIR__ . '/autoload.php';

use http\Router;
use http\AuthMiddleware;
use controllers\HomeController;
use controllers\LoginController;
use controllers\SignupController;

date_default_timezone_set('Europe/Paris');
session_start();

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [LoginController::class, 'index']);
$router->get('/signup', [SignupController::class, 'index']);
$router->post('/signup', [SignupController::class, 'store']);

// $router->get('/profile', [ProfileController::class, 'index'], [
//     'middlewares' => [AuthMiddleware::class]
// ]);

$router->run();
