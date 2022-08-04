<?php
namespace util;

class strings {
    const STRING_PATTERN = '/^[\w]+$/';
    const QUERY_PATTERN = '/^[\w\/\.?=&]+$/';
    
    public static function sanitize(string $string, string $pattern = '/[^\w]/'): string {
        return preg_replace($pattern, '', $string);
    }

    public static function match_pattern(string $subject, string $pattern = ''): bool {
        if (!$pattern)
            $pattern = static::STRING_PATTERN;
        return preg_match($pattern, $subject) === 1;
    }
}
