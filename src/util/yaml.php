<?php
namespace util;

class yaml {
    public static function parse(string $filepath) {
        if (!$filepath)
            return false;
        if(!$encoded = yaml_parse_file($filepath))
            return false;
        return $encoded;
    }
}
