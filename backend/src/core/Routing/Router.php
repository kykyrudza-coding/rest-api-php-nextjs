<?php

namespace Kernel\Backend\Routing;

use Exception;
use Kernel\Backend\Http\Request;
use Kernel\Backend\Http\Response;
use Throwable;

class Router
{
    private array $routes = [];

    public function dispatch(array $routes, array $requestInfo, Request $request, Response $response): void
    {
        $this->routeDistributor($routes);
        $method = strtoupper($requestInfo['method'] ?? 'GET');
        $path = $request->path();

        try {
            $queryParams = $request->getQueryParams();

            foreach ($this->routes[$method] ?? [] as $route) {
                $params = $this->matchRoute($route, $path);

                if ($params === null) {
                    continue;
                }

                $request->merge($params);
                $request->merge($queryParams);

                $response->send($this->resolveHandler($route, $request));

                return;
            }

            if ($this->routeExistsForAnotherMethod($path, $method)) {
                $response->send(['error' => 'Method not allowed'], 405);

                return;
            }

            $response->send(['error' => 'Page not found'], 404);
        } catch (Throwable $e) {
            $response->send(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @throws Exception
     */
    public function generateUrlFromNameRoute(string $name, array $params = []): string
    {
        foreach (Route::getRoutes() as $route) {
            if ($route->getName() === $name) {
                $uri = $route->uri();
                foreach ($params as $key => $value) {
                    $uri = str_replace('{'.$key.'}', rawurlencode((string) $value), $uri);
                }

                if (preg_match('/\{[a-zA-Z_][a-zA-Z0-9_]*}/', $uri)) {
                    throw new Exception("Missing params for route $name.");
                }

                return $uri;
            }
        }
        throw new Exception("Route with name $name not found.");
    }

    private function routeDistributor(array $routes): void
    {
        $this->routes = [];

        foreach ($routes as $route) {
            $this->routes[strtoupper($route->method())][] = $route;
        }
    }

    private function matchRoute(RouteConfig $route, string $path): ?array
    {
        $pattern = preg_replace(
            '/\\\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\\\}/',
            '(?P<$1>[^/]+)',
            preg_quote($route->uri(), '/')
        );

        if (! preg_match('/^'.$pattern.'$/', $path, $matches)) {
            return null;
        }

        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    private function routeExistsForAnotherMethod(string $path, string $method): bool
    {
        foreach ($this->routes as $routeMethod => $routes) {
            if ($routeMethod === $method) {
                continue;
            }

            foreach ($routes as $route) {
                if ($this->matchRoute($route, $path) !== null) {
                    return true;
                }
            }
        }

        return false;
    }

    private function resolveHandler(RouteConfig $route, Request $request): mixed
    {
        if (is_array($route->handler())) {
            [$controller, $action] = $route->handler();
            $controllerInstance = new $controller;

            return call_user_func([$controllerInstance, $action], $request);
        }

        return call_user_func($route->handler(), $request);
    }
}
