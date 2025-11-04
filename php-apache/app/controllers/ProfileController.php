<?php

namespace controllers;

use http\Response;
use http\SessionManager;
use database\Postgresql;
use repositories\SQLUserRepository;
use services\UserService;

class ProfileController
{
    private UserService $userService;
    private SessionManager $session;

    public function __construct()
    {
        $db = new Postgresql(
            getenv('POSTGRES_HOST'),
            (int)getenv('POSTGRES_PORT')
        );

        $userRepository = new SQLUserRepository($db->getConnection());
        $this->userService = new UserService($userRepository);
        $this->session = SessionManager::getInstance();
    }

    public function index()
    {
        $title = 'Camagru - Profile';
        $success = $this->session->getFlash('success', []);
        $errors = $this->session->getFlash('errors', []);
        $old = $this->session->getFlash('old', []);
        $user = $this->session->get('user');

        $email = $old['email'] ?? $user->getEmail();
        $username = $old['username'] ?? $user->getUsername();

        require_once dirname(__DIR__) . '/views/profile/index.php';
    }

    public function update()
    {
        $updateResult = $this->userService->updateUser($_POST);

        if (!$updateResult['success']) {
            $this->session->flash('errors', $updateResult['errors']);
            $this->session->flash('old', $updateResult['data']);

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/profile');
            $response->send();
            exit;
        }

        $this->session->set('user', $updateResult['user']);

        $this->session->flash('success', 'Profile updated successfully.');

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/profile');
        $response->send();
        exit;
    }
}
