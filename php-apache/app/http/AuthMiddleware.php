<?php

namespace http;

use http\Request;
use http\Response;

class AuthMiddleware
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = SessionManager::getInstance();
    }

    public function handle(Request $request, callable $next)
    {
        if (!$this->session->has('user')) {
            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/');
            $response->send();
            exit;
        }
        return $next($request);
    }
}
