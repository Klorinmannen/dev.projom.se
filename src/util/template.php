<?php
namespace util;

class template {
    public static function bind(string $template,
                                array $vars) {
        $replacement_vars = array_values($vars);
        $templated_vars = static::get_templated_vars(array_keys($vars));
        return str_replace($templated_vars, $replacement_vars, $template);
    }

    public static function get_templated_vars(array $subjects,
                                              string $prefix = '{{',
                                              string $postfix = '}}'): array {
        $prefixed_subjects = preg_filter('/^/', $prefix, $subjects);
        return preg_filter('/$/', $postfix, $prefixed_subjects);
    }
}
