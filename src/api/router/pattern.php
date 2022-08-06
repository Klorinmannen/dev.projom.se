<?php

declare(strict_types=1);

namespace api\router;

class pattern
{
    public const PARAM_PATTERNS = [
        '/{id}/' => '(^[1-9]+)',
        '/{name}/' => '([a-zA-Z]+)',
        '/{state}/' => '([a-zA-Z]+)'
    ];

    public static function get_list(array $routes)
    {
        $route_patterns = [];
        foreach ($routes as $route => $route_endpoint) {
            $route_pattern = static::create_from_route($route);
            $route_patterns[$route_pattern] = $route_endpoint;
        }
        return $route_patterns;
    }

    public static function create_from_route(string $route): string
    {
        $route_pattern = $route;
        foreach (static::PARAM_PATTERNS as $param => $pattern)
            $route_pattern = preg_replace($param, $pattern, $route_pattern);

        $route_pattern = preg_replace('/\//', '\/', $route_pattern);

        $template = '/^{{route}}$/';
        $vars = ['route' => $route_pattern];
        return \util\template::bind($template, $vars);
    }
}
