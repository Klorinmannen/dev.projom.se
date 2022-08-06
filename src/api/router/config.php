<?php

declare(strict_types=1);

namespace api\router;

class config
{
    public const CONFIG_DIR = 'html/conf/';

    public static function get_routes(): array
    {
        //if (array_key_exists('api_routes', $_SESSION))
        //  return $_SESSION['api_routes'];

        $main_conf_file = static::main_config();
        $paths = $main_conf_file['paths'];
        $routes = [];
        foreach ($paths as $uri_path => $path) {
            $endpoints = explode('#/', $path['$ref']);
            $conf_file = $endpoints[0];
            $conf_name = $endpoints[1];
            $resource = str_replace('.yml', '', $conf_file);

            $ref_config = static::referenced_config($conf_file);
            $conf_details = $ref_config[$conf_name];
            foreach ($conf_details as $method => $details) {
                $routes[$method][$uri_path]['endpoint'] = $details['operationId'];
                $routes[$method][$uri_path]['security'] = $details['security'];
                $routes[$method][$uri_path]['resource'] = $resource;
            }
        }

        $_SESSION['api_config'] = $routes;
        return $routes;
    }

    public static function main_config(): array
    {
        $main_config_path = static::main_config_path();
        $main_config_filepath = $main_config_path . 'main.yml';
        if (!is_readable($main_config_filepath))
            throw new \Exception('Missing main api configuration file.', 500);
        return \util\yaml::parse($main_config_filepath);
    }

    public static function main_config_path(): string
    {
        return \system::config()->dir(static::CONFIG_DIR);
    }

    public static function referenced_config(string $referenced): array
    {
        $main_config_path = static::main_config_path();
        $referenced_config_filepath = $main_config_path . $referenced;
        if (!is_readable($referenced_config_filepath))
            throw new \Exception('Missing referenced api configuration file.', 500);
        return \util\yaml::parse($referenced_config_filepath);
    }
}
