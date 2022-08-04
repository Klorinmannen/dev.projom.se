<?php
declare(strict_types=1);
namespace http;

class router {

    public static function map(): void {
        $request = \http\request::get();       

        $url_path = $request->get_url_path();
        $controller = '\dice\page\controller';
        if ($request->get_type() == request::API_REQ)
            $controller = '\api\controller';

        $controller::route();                    
    }      
}
