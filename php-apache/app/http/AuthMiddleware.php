<?php

namespace http;

use http\Request;
use http\Response;

class AuthMiddleware
{
    public function handle(Request $request, callable $next)
    {
        if (!isset($_SESSION['user'])) {
            $response = new Response(Response::HTTP_SEE_OTHER);
            $response->addHeader('Location', '/');
            $response->send();
            exit;
        }
        return $next($request);
    }
}
