<?php
declare(strict_types=1);
namespace api;

class router {
    private $_request = null;
    private $_config = null;
    private $_resource_controller = '';
    private $_resource_model = '';
    private $_resource_method = '';
    private $_path_parameters = [];
    private $_route = [];
    
    public const RESOURCE_CONTROLLER = '\\api\\controller';
    public const RESOURCE_MODEL = '\\api\\model';
    public const SECURITY = false;
    
    public function __construct() { 
        $this->_request = \http\request::get();
        $this->_config = new \api\route\config();
    }
    
    public function map(): void {
        $routes = self::routes();
        $url_path = self::url_path();
       
        // Match request-path against defined path patterns endpoints
        if (!$matched = self::match_path_route($routes, $url_path))
            throw new \Exception('Endpoint not found', 400);

        if (static::SECURITY && $matched['security'])
            if (!self::validate_security())
                throw new \Exception('Authorization error', 401);

        // The first match is the matched path itself
        array_shift($matched['path_parameters']);

        $resource = self::prepare_resource($matched['resource']);

        $this->_resource_controller = self::create_resource_controller($resource);
        $this->_resource_model = self::create_resource_model($resource);
        $this->_resource_method = $matched['endpoint'];
        $this->_path_parameters = $matched['path_parameters'];
        $this->_route = self::create_route();

        self::validate_route();        
    }
  
    private function routes(): array {
        $method = strtolower($this->_request->get_method());
        $routes = $this->_config->get_routes();

        if (!$routes = \util\validate::array_key($routes, $method))
            throw new \Exception('No endpoints found with the used http method', 400);

        // Hell breaks loose if the ksort is removed.
        ksort($routes);

        return route\pattern::get_list($routes);
    }
    
    private function url_path(): string {
        $url_path = $this->_request->get_url_path();
        return str_replace('api', '', $url_path);
    }

    private function match_path_route(array $routes, string $url): array {
        foreach ($routes as $route_pattern => $route)        
            if (preg_match($route_pattern, $url, $parameters) === 1)
                return [ 'path_parameters' => $parameters,
                         'endpoint' => $route['endpoint'],
                         'resource' => $route['resource'],
                         'security' => $route['security'] ];
        return [];
    }
    
    private function validate_security(): bool {
        if (!$jwt = $this->_request->get_header_auth())
            return false;
        if (!\util\jwt::validate($jwt))
            return false;
        return true;
    }

    private function prepare_resource(string $resource): string {
        $resource = trim($resource, '/');
        $resource = str_replace('/', '\\', $resource);
        return $resource;
    }

    private function create_resource_controller(string $resource): string {
        return $resource.static::RESOURCE_CONTROLLER;
    }

    private function create_resource_model(string $resource): string {
        return $resource.static::RESOURCE_MODEL;
    }

    private function create_route(): array {
        return [ 'resource_method' => $this->_resource_method,
                 'call_data' => self::call_data(),
                 'resource_controller' => $this->_resource_controller,
                 'resource_model' => $this->_resource_model ];
    }

    public function call_data(): array {
        return [ 'path_parameters' => $this->_path_parameters,
                 'json_data' => $this->_request->get_json_data(),
                 'query_parameters' => $this->_request->get_query_parameters() ];
    }

    private function validate_route(): void {
        if (!class_exists($this->_resource_controller))
            throw new \Exception('Resource not found', 500);        
        if (!method_exists($this->_resource_controller, $this->_resource_method))
            throw new \Exception('Resource endpoint not found', 500);
        if (!class_exists($this->_resource_model))
            throw new \Exception('Resource model not found', 500);
    }
    
    public function route(): array { return $this->_route; }
}
