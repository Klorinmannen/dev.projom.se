<?php
namespace api;

class request extends \util\request {
    
    public function __construct() {
        parent::__construct();
        self::modify_url();
    }

    private function modify_url(): void {
        $this->_url = str_replace('/api', '', $this->_url);
    }
}
