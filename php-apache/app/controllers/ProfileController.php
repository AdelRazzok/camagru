<?php

namespace controllers;

use http\SessionManager;

class ProfileController
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = SessionManager::getInstance();
    }

    public function index()
    {
        $title = 'Camagru - Profile';
        $errors = $this->session->getFlash('errors', []);

        $user = $this->session->get('user');

        require_once dirname(__DIR__) . '/views/profile/index.php';
    }
}
