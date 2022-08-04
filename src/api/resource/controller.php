<?php
declare(strict_types=1);
namespace api\resource;

class controller {
    private $_resource_method = '';
    protected $_resource_model = '';
    protected $_path_parameters = [];
    protected $_query_parameters = [];    
    protected $_json_data = [];
    
    public function __construct(string $resource_method,
                                string $resource_model,
                                array $call_data = []) {

        $this->_resource_method = $resource_method;
        $this->_resource_model = new $resource_model();

        $this->_path_parameters = $call_data['path_parameters'];
        $this->_query_parameters = $call_data['query_parameters'];
        $this->_json_data = $call_data['json_data'];
    }

    public function call(): string {
        $call_method = $this->_resource_method;
        $response = $this->$call_method(...$this->_path_parameters);
        
        return self::get_json_encoded_response($response);
    }

    public function get_json_encoded_response(array $response): string {
        $pretty_print = false;
        return \util\json::encode($response, $pretty_print);
    }
}
