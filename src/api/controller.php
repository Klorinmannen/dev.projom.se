<?php
declare(strict_types=1);
namespace api;

class controller
{
    public static function route(): void
    {
        ob_clean();
        
        // Route new request
        $router = new router();
        $router->map();
        $route = $router->route();

        // Make endpoint call
        $resource_controller = $route['resource_controller'];
        $controller = new $resource_controller($route['resource_method'],
                                               $route['resource_model'],
                                               $route['call_data']);
        $response = $controller->call();

        header('Content-Type: application/json; charset=utf-8');
        echo $response;
        exit;
    }
}
