<?php
declare(strict_types=1);
namespace dice\page;

class view
{
    public static function page(): void
    {
        $template_file = __DIR__.'/view.html';
        $template = \util\file\read::from_filepath($template_file);
        $vars = [ 'dice_roll' => \dice::roll() ];
        echo \util\template::bind($template, $vars);
    }
}
