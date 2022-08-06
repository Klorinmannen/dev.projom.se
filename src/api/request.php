<?php

declare(strict_types=1);

namespace api;

class request extends \http\request
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_url_path(): string
    {
        return str_replace('api', '', $this->_url_path);
    }
}
