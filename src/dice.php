<?php
declare(strict_types=1);

class dice
{
    public static function roll(int $faces = 6): int {
        if (!$faces)
            return 0;        
        return rand(1, $faces);
    }
}
