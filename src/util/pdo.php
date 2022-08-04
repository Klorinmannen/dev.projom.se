<?php
namespace util;

class pdo {
    public static function init(array $config): object {
        $dsn = \util\database::dsn($config);
        $driver_options = static::get_driver_opts();
        $pdo = new \PDO($dsn, $config['username'], $config['password'], $driver_options);
        if (!$pdo)
            throw new \Exception('Failed to create pdo');
        return $pdo;
    }

    private static function get_driver_opts(): array {
        return [ \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                 \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ];
    }
}
