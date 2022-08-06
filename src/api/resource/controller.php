<?php

declare(strict_types=1);

namespace api\resource;

abstract class controller
{
    protected $_path_parameters = [];
    protected $_query_parameters = [];
    protected $_json_data = [];

    public function __construct(array $parameter_data)
    {
        $this->_path_parameters = $parameter_data['path_parameters'];
        $this->_query_parameters = $parameter_data['query_parameters'];
        $this->_json_data = $parameter_data['json_data'];
    }

    abstract public function response(array $raw_response): array;
    abstract public function validate(array $response): bool;
}
