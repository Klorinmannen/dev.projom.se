<?php
namespace util;

class debug {
    public static function sql(string $sql, string $lb = "\n") {
        $output = preg_split('/(SELECT|FROM|WHERE|INSERT INTO|VALUES|INNER JOIN|ON|LEFT JOIN|RIGHT JOIN)/',
                             $sql,
                             -1,
                             \PREG_SPLIT_NO_EMPTY|\PREG_SPLIT_DELIM_CAPTURE);
        return implode($lb, $output);
    }

    public static function as_string(array $subject) {
        return var_export($subject);
    }

    public static function out($subject) {
        echo '<pre>';
        print_r($subject);
        echo '</pre>';
    }

    public static function dump($subject) {
        echo '<pre>';
        var_dump($subject);
        echo '</pre>';        
    }
}
