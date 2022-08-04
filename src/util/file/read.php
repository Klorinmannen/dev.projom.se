<?php
namespace util\file;

class read {
    public static function from_filepath(string $full_filepath): string {
        if (!$full_filepath)
            return '';
        $pathinfo = pathinfo($full_filepath);
        if (!\util\validate::query_key($pathinfo, 'basename'))
            throw new \Exception('Filename not present with path?', 500);
        if (!is_readable($full_filepath))
            throw new \Exception('File is not readable!', 500);            
        if (!$string = file_get_contents($full_filepath))
            throw new \Exception('Failed to get contents!', 500);

        return $string;
    }
}
