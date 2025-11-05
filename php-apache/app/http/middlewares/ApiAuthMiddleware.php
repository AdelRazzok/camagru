<?php

namespace http\middlewares;

use http\Request;
use http\Response;
use http\SessionManager;

class ApiAuthMiddleware
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = SessionManager::getInstance();
    }

    public function handle(Request $request, callable $next)
    {
        if (!$this->session->has('user')) {
            http_response_code(Response::HTTP_UNAUTHORIZED);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        return $next($request);
    }
}
