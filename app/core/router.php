<?php

namespace Core;

class Router
{
    private static $routes = [];

    public static function get($route, $controller)
    {
        $route = preg_replace('/{(\w+)}/', '(?P<\1>\d+)', trim($route, '/'));
        self::$routes['GET'][$route] = $controller;
    }

    public static function post($route, $controller)
    {
        $route = preg_replace('/{(\w+)}/', '(?P<\1>\d+)', trim($route, '/'));
        self::$routes['POST'][$route] = $controller;
    }

    public static function dispatch($url)
    {
        $url = trim(parse_url($url, PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes[$method] as $route => $controller) {
            if (preg_match("#^$route$#", $url, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$controller, $method] = explode('@', $controller);
                $controller = "App\\Controllers\\$controller";

                if (class_exists($controller) && method_exists($controller, $method)) {
                    call_user_func_array([new $controller, $method], $params);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "Página não encontrada!";
    }
}
