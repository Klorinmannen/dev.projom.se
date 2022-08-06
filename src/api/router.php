<?php

declare(strict_types=1);

namespace api;

class router
{
    private $_request = null;
    private $_router_config = null;
    private $_resource_controller = '';
    private $_data = [];

    public const RESOURCE_CONTROLLER = '\\api\\controller';

    public function __construct(
        object $request,
    ) {
        $this->_request = $request;
        $this->_router_config = new router\config();
    }

    public function map(): void
    {
        $routes = self::routes();
        $url_path = $this->_request->get_url_path();

        // Match request-path against defined path patterns endpoints
        if (!$matched = self::match_path_route($routes, $url_path))
            throw new \Exception('Endpoint not found', 400);

        // The first match is the matched path itself
        array_shift($matched['path_parameters']);

        $resource = self::prepare_resource($matched['resource']);
        $this->_resource_controller = self::create_resource_controller($resource);
        $this->_data = self::create_data($matched);
    }

    private function routes(): array
    {
        $method = strtolower($this->_request->get_method());
        $routes = $this->_router_config->get_routes();

        if (!$routes = \util\validate::array_key($routes, $method))
            throw new \Exception('No endpoints found with the used http method', 400);

        // Hell breaks loose if the ksort is removed.
        ksort($routes);

        return router\pattern::get_list($routes);
    }

    private function match_path_route(
        array $routes,
        string $url
    ): array {

        foreach ($routes as $route_pattern => $route)
            if (preg_match($route_pattern, $url, $parameters) === 1)
                return [
                    'path_parameters' => $parameters,
                    'endpoint' => $route['endpoint'],
                    'resource' => $route['resource'],
                    'security' => $route['security']
                ];

        return [];
    }

    private function prepare_resource(string $resource): string
    {
        $resource = trim($resource, '/');
        $resource = str_replace('/', '\\', $resource);
        return $resource;
    }

    private function create_resource_controller(string $resource): string
    {
        return $resource . static::RESOURCE_CONTROLLER;
    }

    private function create_data(array $matched): array
    {
        return [
            'security' => $matched['security'],
            'resource_method' => $matched['endpoint'],
            'parameter_data' => self::parameter_data($matched),
            'resource_controller' => $this->_resource_controller
        ];
    }

    public function parameter_data(array $matched): array
    {
        return [
            'path_parameters' => $matched['path_parameters'],
            'json_data' => $this->_request->get_json_data(),
            'query_parameters' => $this->_request->get_query_parameters()
        ];
    }

    public function data(): array
    {
        return $this->_data;
    }
}
