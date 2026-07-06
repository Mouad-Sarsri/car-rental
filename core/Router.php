<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Router — Routeur frontal HTTP PSR-4 aware
 * Les controllers sont référencés par leur FQCN : App\Controllers\CarController
 */
class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->add('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->add('POST', $path, $controller, $action);
    }

    private function add(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $this->pathToRegex($path),
            'controller' => $controller,
            'action'     => $action,
            'params'     => $this->extractParamNames($path),
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = '/' . trim(strtok($uri, '?'), '/');

        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = $this->extractParams($route['params'], $matches);
                $this->call($route['controller'], $route['action'], $params);
                return;
            }
        }

        http_response_code(404);
        $this->call('App\\Controllers\\ErrorController', 'notFound', []);
    }

    private function call(string $fqcn, string $action, array $params): void
    {
        if (!class_exists($fqcn)) {
            throw new \RuntimeException("Classe introuvable : {$fqcn}");
        }

        $controller = new $fqcn();

        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Action introuvable : {$fqcn}::{$action}");
        }

        call_user_func_array([$controller, $action], $params);
    }

    private function pathToRegex(string $path): string
    {
        $pattern = preg_replace('/\{[a-z_]+\}/', '([0-9]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function extractParamNames(string $path): array
    {
        preg_match_all('/\{([a-z_]+)\}/', $path, $matches);
        return $matches[1];
    }

    private function extractParams(array $names, array $matches): array
    {
        $values = array_slice($matches, 1);
        $params = [];
        foreach ($names as $i => $name) {
            $params[$name] = isset($values[$i]) ? (int) $values[$i] : null;
        }
        return array_values($params);
    }
}
