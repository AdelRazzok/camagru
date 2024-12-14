<?php

namespace controllers;

class HomeController
{
    public function index()
    {
        $title = 'Camagru - Home';
        require_once dirname(__DIR__) . '/views/home/index.php';
    }
}
