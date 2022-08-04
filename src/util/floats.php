<?php
namespace util;

class floats {
    const FLOAT_PATTERN = '^-?[0-9]\.?,?[0-9]?$';
    
    public static function match_pattern(int $subject, string $pattern = ''): int {
        if (!$pattern)
            $pattern = static::FLOAT_PATTERN;
        return (preg_match($pattern, $subject) === 1);
    }
}
