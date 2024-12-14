<?php

namespace http;

use http\Request;
use Exception;

class Router
{
    private array $routes;
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get(string $route, callable|array $action, array $options = []): void
    {
        $this->addRoute('GET', $route, $action, $options);
    }

    public function post(string $route, callable|array $action, array $options = []): void
    {
        $this->addRoute('POST', $route, $action, $options);
    }

    public function put(string $route, callable|array $action, array $options = []): void
    {
        $this->addRoute('PUT', $route, $action, $options);
    }

    public function patch(string $route, callable|array $action, array $options = []): void
    {
        $this->addRoute('PATCH', $route, $action, $options);
    }

    public function delete(string $route, callable|array $action, array $options = []): void
    {
        $this->addRoute('DELETE', $route, $action, $options);
    }

    public function addRoute(string $method, string $route, callable|array $action, array $options = []): void
    {
        $pattern = '/{([^}]+)}/';

        $routeParams = [];
        if (preg_match_all($pattern, $route, $matches)) {
            $route = preg_replace($pattern, '(.*?)', $route);
            $routeParams = $matches[1];
        }

        $middlewares = $options['middlewares'] ?? [];

        $this->routes[$method][$route] = [
            'action' => $action,
            'route-params' => $routeParams,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(Request $request)
    {
        $uri = $request->getUri();
        $method = $request->getMethod();

        foreach ($this->routes[$method] ?? [] as $route => $details) {
            $pattern = "/^" . str_replace('/', '\/', $route) . "$/";

            if (preg_match($pattern, $uri, $matches)) {
                unset($matches[0]);

                foreach ($details['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $response = $middlewareInstance->handle($request, function() {
                        return null;
                    });

                    if ($response instanceof Response) {
                        return $response->send();
                    }
                }

                $action = $details['action'];
                list($controller, $method) = $action; 
  
                $controllerInstance = new $controller();
                return call_user_func_array([$controllerInstance, $method], $matches);
            }
        }
        throw new Exception('Route not found');
    }

    public function run()
    {
        $this->dispatch($this->request);
    }
}
