<?php

declare(strict_types=1);

namespace api;

class route
{
    private $_route_security = false;
    private $_resource_method = '';
    private $_parameter_data = [];
    private $_resource_controller = '';
    private $_auth_data = [];

    public function __construct(
        array $router_data,
        object $request,
        object $config
    ) {

        $this->_route_security = $router_data['security'];
        $this->_resource_method = $router_data['resource_method'];
        $this->_resource_controller = $router_data['resource_controller'];
        $this->_parameter_data = $router_data['parameter_data'];

        $this->_auth_data = [
            'config_security' => $config['security'],
            'route_security' => $this->_route_security,
            'auth_header' => $request->get_header_auth()
        ];
    }

    public function call(): string
    {
        $call_method = $this->_resource_method;
        $called_resource = $this->called_resource();
        $response = $called_resource->{$call_method}();

        return self::get_json_encoded_response($response);
    }

    private function called_resource(): object
    {
        $called_resource = $this->_resource_controller;
        return new $called_resource($this->_parameter_data);
    }

    private function get_json_encoded_response(array $response): string
    {
        $pretty_print = false;
        return \util\json::encode($response, $pretty_print);
    }

    public function validate(): void
    {
        if (!class_exists($this->_resource_controller))
            throw new \Exception('Resource not found', 400);

        if (!method_exists($this->_resource_controller, $this->_resource_method))
            throw new \Exception('Resource endpoint not found', 400);

        if (!route\auth::validate($this->_auth_data))
            throw new \Exception('Authorization error', 401);
    }
}
