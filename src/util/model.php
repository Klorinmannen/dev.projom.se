<?php
namespace util;

class model {
    protected function query(string $table_name): object {
        return new \util\table($table_name);
    }    
}
