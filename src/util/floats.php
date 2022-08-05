<?php

declare(strict_types=1);

namespace util;

class floats
{
    const FLOAT_PATTERN = '^-?[0-9]\.?,?[0-9]?$';

    public static function match_pattern(float $subject, string $pattern = ''): bool
    {
        if (!$pattern)
            $pattern = static::FLOAT_PATTERN;
        return (preg_match($pattern, (string)$subject) === 1);
    }
}
