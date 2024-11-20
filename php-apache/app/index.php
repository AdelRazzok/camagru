<?php

require_once __DIR__ . '/autoload.php';

use app\http\Router;
use app\controllers\HomeController;

session_start();


$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->run();
