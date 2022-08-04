<?php
namespace util;

class base64 {    
    public static function encode_url(string $string) {
        return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
    }

    public static function decode_url(string $string) {
        return base64_decode(str_replace(['-','_'], ['+','/'], $string));
    }   

    public static function encode(string $string) {
        return base64_encode($string);
    }

    public static function decode(srting $string) {
        return base64_decode($string);
    }    
}
