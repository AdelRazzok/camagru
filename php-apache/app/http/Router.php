<?php

namespace app\http;

use app\http\Request;

class Router
{
    private string $url;
    private array $routes;
    private Request $request;
    private array $middlewares;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get(string $route, string $controller, array $params): void
    {
        $this->addRoute('GET', $route, $controller, $params);
    }

    public function post(string $route, string $controller, array $params): void
    {
        $this->addRoute('POST', $route, $controller, $params);
    }

    public function put(string $route, string $controller, array $params): void
    {
        $this->addRoute('PUT', $route, $controller, $params);
    }

    public function patch(string $route, string $controller, array $params): void
    {
        $this->addRoute('PATCH', $route, $controller, $params);
    }

    public function delete(string $route, string $controller, array $params): void
    {
        $this->addRoute('DELETE', $route, $controller, $params);
    }

    public function addRoute(string $method, string $route, callable|array $action, array $rules = ['@', '?']): void
    {
        $pattern = '/{([^}]+)}/';

        $routeParams = [];
        if (preg_match_all($pattern, $route, $matches)) {
            $route = preg_replace($pattern, '(.*?)', $route);
            $routeParams = $matches[1];
        }

        $this->routes[$method][$route] = [
            'action' => $action,
            'route-params' => $routeParams,
            'rules' => $rules,
        ];
    }
}
