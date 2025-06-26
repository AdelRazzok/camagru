<?php

namespace controllers;

use http\Response;
use database\Postgresql;
use http\SessionManager;
use repositories\SQLUserRepository;
use services\UserService;

class LoginController
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
        $title = 'Camagru - Login';
        $success = $this->session->getFlash('success', []);
        $error = $this->session->getFlash('error', []);
        $old = $this->session->getFlash('old', []);

        require_once dirname(__DIR__) . '/views/login/index.php';
    }

    public function authenticate()
    {
        $result = $this->userService->authenticateUser($_POST['username'], $_POST['password']);

        if (!$result['success']) {
            $this->session->flash('error', $result['error']);
            $this->session->flash('old', [
                'username' => $_POST['username']
            ]);

            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/login');
            $response->send();
            exit;
        }

        $this->session->set('user', $result['user']);

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/');
        $response->send();
        exit;
    }

    public function logout()
    {
        $this->session->destroy();

        $response = new Response(Response::HTTP_SEE_OTHER);
        $response->addHeader('Location', '/');
        $response->send();
        exit;
    }
}
