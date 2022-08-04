<?php
namespace util;

class js {
    public static function get_file(string $script_path = '') {
        if (!$script_path)
            return '';

        $script = file_get_contents($script_path);
        if($script === false)
            throw new \Exception('Failed to get file,', 500);

        return static::add_tags($script);
    }

    public static function add_tags(string $script) {
        return '<script>'.$script.'</script>';
    }
}
