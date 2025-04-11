<?php

namespace controllers;

class SignupController
{
    public function index()
    {
        $title = 'Camagru - Sign up';
        require_once dirname(__DIR__) . '/views/signup/index.php';
    }

    public function store() {}
}
