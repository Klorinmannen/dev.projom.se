<?php
declare(strict_types=1);

namespace util;

class validate {
    public static function float(float $float): bool {
        return floats::match_pattern($float);
    }
    
    public static function id(int $id): bool {
        return integers::match_pattern($id, integers::ID_PATTERN);
    }

    public static function int(int $int): bool {
        return integers::match_pattern($int);
    }

    public static function string(string $string): bool {
        return strings::match_pattern($string);
    }

    public static function query(string $query): bool {
        return strings::match_pattern($query, strings::QUERY_PATTERN);
    }

    public static function id_key(array $container, string $key): int {
        if (!static::key_exists($container, $key))
            return 0;
        $id_subject = $container[$key];
        if (!static::id($id_subject))
            return 0;
        return (int)$id_subject;
    }

    public static function float_key(array $container, string $key): float {
        if (!static::key_exists($container, $key))
            return 0;
        $float_subject = $container[$key];
        if (!static::float($float_subject))
            return 0;
        return (float)$float_subject;
    }
    
    public static function int_key(array $container, string $key): int {
        if (!static::key_exists($container, $key))
            return 0;
        $int_subject = $container[$key];
        if (!static::int($int_subject))
            return 0;
        return (int)$int_subject;
    }

    public static function query_key(array $container, string $key): string {
        if (!static::key_exists($container, $key))
            return '';
        $query_subject = $container[$key];
        if (!static::query($query_subject))
            return '';
        return (string)$query_subject;
    }    
    
    public static function string_key(array $container, string $key): string {
        if (!static::key_exists($container, $key))
            return '';
        $string_subject = $container[$key];
        if (!static::string($string_subject))
            return '';
        return (string)$string_subject;
    }    

    public static function array_key(array $container, string $key): array {
        if (!static::key_exists($container, $key))
            return [];
        $array_subject = $container[$key];
        if (!is_array($array_subject))
            return [];
        return $array_subject;
    }    

    public static function float_request(string $subject, array $default_return = []): float {
        if (!static::key_exists($default_return, 'default'))
            $default_return['default'] = 0;
        if (!$int = static::float_key($_REQUEST, $subject))
            return $default_return['default'];
        return (float)$int;
    }
    
    public static function int_request(string $subject, array $default_return = []): int {
        if (!static::key_exists($default_return, 'default'))
            $default_return['default'] = 0;
        if (!$int = static::int_key($_REQUEST, $subject))
            return $default_return['default'];
        return (int)$int;
    }

    public static function id_request(string $subject, array $default_return = []): int {
        if (!static::key_exists($default_return, 'default'))
            $default_return['default'] = 0;
        if (!$id = static::id_key($_REQUEST, $subject))
            return $default_return['default'];
        return (int)$id;
    }

    public static function string_request(string $subject, array $default_return = []): string {
        if (!static::key_exists($default_return, 'default'))
            $default_return['default'] = '';
        if (!$string = static::string_key($_REQUEST, $subject))
            return $default_return['default'];
        return $string;
    }

    private static function key_exists(array $container, string $key): bool {
        if (!$key || !$container)
            return false;
        if (!array_key_exists($key, $container))
            return false;
        return true;
    }    
}
