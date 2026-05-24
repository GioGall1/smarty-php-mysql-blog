<?php

declare(strict_types=1);

namespace App\Core;

use ReflectionMethod;
use ReflectionNamedType;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][] = [
            'path' => $path,
            'handler' => $handler,
            ...$this->compileRoute($path),
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === false) {
            $this->notFound();

            return;
        }

        $route = $this->matchRoute($this->normalizeMethod($method), $path);

        if ($route === null) {
            $this->notFound();

            return;
        }

        [$controllerClass, $action] = $route['handler'];

        $view = new View();
        $controller = new $controllerClass($view);

        if (!method_exists($controller, $action)) {
            $this->notFound();

            return;
        }

        $parameters = $this->resolveParameters($controllerClass, $action, $route['parameters']);

        if ($parameters === null) {
            $this->notFound();

            return;
        }

        $controller->$action(...$parameters);
    }

    private function matchRoute(string $method, string $path): ?array
    {
        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches) !== 1) {
                continue;
            }

            $parameters = [];

            foreach ($route['parameterNames'] as $name) {
                $value = $matches[$name];
                $parameters[$name] = ctype_digit($value) ? (int) $value : $value;
            }

            $route['parameters'] = $parameters;

            return $route;
        }

        return null;
    }

    private function normalizeMethod(string $method): string
    {
        return $method === 'HEAD' ? 'GET' : $method;
    }

    private function compileRoute(string $path): array
    {
        $parameterNames = [];
        $pattern = preg_replace_callback(
            '/\\\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\\\}/',
            static function (array $matches) use (&$parameterNames): string {
                $parameterNames[] = $matches[1];

                return sprintf('(?P<%s>[^/]+)', $matches[1]);
            },
            preg_quote($path, '#')
        );

        return [
            'pattern' => '#^' . $pattern . '$#',
            'parameterNames' => $parameterNames,
        ];
    }

    private function resolveParameters(string $controllerClass, string $action, array $parameters): ?array
    {
        $reflection = new ReflectionMethod($controllerClass, $action);
        $resolvedParameters = [];

        foreach ($reflection->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (!array_key_exists($name, $parameters)) {
                return null;
            }

            $value = $parameters[$name];
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType || !$type->isBuiltin()) {
                $resolvedParameters[] = $value;
                continue;
            }

            $resolvedValue = match ($type->getName()) {
                'int' => is_int($value) ? $value : null,
                'string' => is_string($value) ? $value : null,
                default => $value,
            };

            if ($resolvedValue === null && !$type->allowsNull()) {
                return null;
            }

            $resolvedParameters[] = $resolvedValue;
        }

        return $resolvedParameters;
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404 Not Found';
    }
}
