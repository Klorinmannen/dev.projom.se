<?php

declare(strict_types=1);

namespace api;

class controller
{
    public static function route(): void
    {
        ob_clean();

        $request = new request();
        $config = new \system\config();

        // Route new request
        $router = new router($request, $config);
        $router->map();
        $router_data = $router->data();

        $route = new route($router_data, $request, $config);
        $route->validate();

        // Make endpoint call
        $response = $route->call();

        header('Content-Type: application/json; charset=utf-8');
        echo $response;
        exit;
    }
}
