<?php

namespace Kernel\Backend\Routing;

use Exception;
use Kernel\Backend\Http\Request;
use Kernel\Backend\Http\Response;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function dispatch(array $routes, array $requestInfo, Request $request, Response $response): void
    {
        $this->routeDistributor($routes);
        $routeFound = false;

        try {
            $queryParams = $request->getQueryParams();
            $uriWithoutQuery = strtok($requestInfo['uri'], '?');

            foreach ($this->routes[$requestInfo['method']] as $route) {
                $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)}/', '(?P<$1>[^/]+)', $route->uri());
                $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

                if (preg_match($pattern, $uriWithoutQuery, $matches)) {
                    $routeFound = true;
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $request->merge($params);
                    $request->merge($queryParams);

                    if (is_array($route->handler())) {
                        [$controller, $action] = $route->handler();
                        $controllerInstance = new $controller;
                        $responseContent = call_user_func([$controllerInstance, $action], $request);
                    } else {
                        $closure = $route->handler();
                        $responseContent = call_user_func($closure, $request);
                    }

                    $response->send($responseContent);
                    break;
                }
            }

            if (! $routeFound) {
                $response->send(['error' => 'Page not found'], 404);
            }
        } catch (Exception $e) {
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
                    $uri = str_replace('{' . $key . '}', $value, $uri);
                }
                return $uri;
            }
        }
        throw new Exception("Route with name $name not found.");
    }

    private function routeDistributor(array $routes): void
    {
        foreach ($routes as $route) {
            $this->routes[$route->method()][] = $route;
        }
    }
}