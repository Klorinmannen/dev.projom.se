<?php

declare(strict_types=1);

namespace util;

class json
{
    public static function parse(string $path): array
    {
        if (!$path)
            return [];

        $json_string = static::get_string($path);
        return static::decode($json_string);
    }

    public static function get_string(string $path): string
    {
        if (!$path)
            return '';

        if (!$json_string = file_get_contents($path))
            return '';

        return $json_string;
    }

    public static function decode(
        string $json_string,
        bool $as_array = true
    ): array {

        if (!$json_string)
            return [];

        if (!$content = json_decode($json_string, $as_array))
            return [];

        return $content;
    }

    public static function encode(
        array $to_encode,
        bool $pp = false
    ): string {

        if (!$to_encode)
            return '';

        $encoded = $pp === true ? json_encode($to_encode, JSON_PRETTY_PRINT) : json_encode($to_encode);
        if (!$encoded)
            return '';

        return $encoded;
    }
}
