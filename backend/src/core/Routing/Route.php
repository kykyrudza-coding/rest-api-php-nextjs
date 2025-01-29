<?php

namespace Kernel\Backend\Routing;

use Closure;

class Route extends RouteConfig
{

    private static array $routes = [];

    public static function get(string $uri, Closure|array $handler): RouteConfig
    {
        return self::addRoute('GET', $uri, $handler);
    }

    public static function post(string $uri, Closure|array $handler): RouteConfig
    {
        return self::addRoute('POST', $uri, $handler);
    }

    public static function addRoute(string $method, string $uri, Closure|array $handler): RouteConfig
    {
        $route = new RouteConfig($method, $uri, $handler);
        self::$routes[] = $route;

        return $route;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }
}
