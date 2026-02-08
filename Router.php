<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => trim($uri, '/'),
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method)
    {
        $uri = trim($uri, '/');

        // Remove query string variables
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                // Instantiate Controller
                $controllerName = "App\\Controllers\\" . $route['controller'];
                $controller = new $controllerName();

                $actionName = $route['action'];
                return $controller->$actionName();
            }
        }

        // 404
        echo "404 Not Found (URI: $uri)";
    }
}
