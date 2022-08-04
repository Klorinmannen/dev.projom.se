<?php
namespace util;

class database {   
    public static function dsn(array $config): string {
        return sprintf( 'mysql:host=%s;port=%d;dbname=%s', 
                        $config['server_host'], 
                        $config['server_port'], 
                        $config['database_name'] );
    }
}
