<?php
declare(strict_types=1);
namespace http;

class router {

    public static function map(): void { 
        $controller = '\dice\page\controller';
        $controller::route();                    
    }      
}
