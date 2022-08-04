<?php
namespace util\file;

class write {
    public static function to_filepath(string $filepath, string $data, int $flags = LOCK_EX): int {
        if (!$filepath || !$data)
            return 0;

        $pathinfo = pathinfo($filepath);
        if (!\util\validate::query_key($pathinfo, 'basename'))
            throw new \Exception('Filename not present with path?', 500);

        if (!$folder = \util\validate::query_key($pathinfo, 'dirname'))
            throw new \Exception('File directory not present in path?', 500);
        if (!file_exists($folder))
            throw new \Exception('File directory does not exists!', 500);            

        if (file_exists($filepath))
            if (!is_writeable($filepath))
                throw new \Exception('File is not writeable!', 500);
        
        $write_result = file_put_contents($filepath, $data, $flags);
        if ($write_result === false)
            throw new \Exception('Failed write to file', 500);

        return $write_result;
    }    
}
