<?php

require_once __DIR__ . '/autoload.php';

use http\Router;
use controllers\HomeController;

session_start();


$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->run();
