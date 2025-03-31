<?php

namespace controllers;

class LoginController
{
    public function index()
    {
        $title = 'Camagru - Login';
        require_once dirname(__DIR__) . '/views/login/index.php';
    }
}
