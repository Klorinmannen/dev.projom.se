<?php

declare(strict_types=1);

namespace util;

class integers
{
    const ID_PATTERN = '/^[0-9]+$/';
    const INT_PATTERN = '/^-?[0-9]+$/';

    public static function match_pattern(int $subject, string $pattern = ''): bool
    {
        if (!$pattern)
            $patter = static::INT_PATTERN;
        return (preg_match($pattern, (string)$subject) === 1);
    }
}
