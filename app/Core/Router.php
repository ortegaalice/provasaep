<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $action): void { $this->add('GET', $path, $action); }
    public function post(string $path, array $action): void { $this->add('POST', $path, $action); }

    private function add(string $method, string $path, array $action): void
    {
        $this->routes[$method][$path] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $action = $this->routes[$method][$uri] ?? null;
        if (!$action) {
            http_response_code(404);
            echo 'Página não encontrada.';
            return;
        }
        [$controller, $handler] = $action;
        (new $controller())->$handler();
    }
}
