<?php
namespace util;

class curl {
    const TIME_OUT_SECONDS = 3;

    public static function get(string $url) {

        $handle = curl_init();
        curl_setopt($handle, \CURLOPT_HTTPGET, true);        
        curl_setopt($handle, \CURLOPT_URL, $url);        
        curl_setopt($handle, \CURLOPT_HEADER, false);
        curl_setopt($handle, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, \CURLOPT_TIMEOUT, static::TIME_OUT_SECONDS);                             

        $result = curl_exec($handle);
        curl_close($handle);
        return $result;
    }

    public static function get_put(string $url, string $filename) {
        if (!$response = \util\curl::get($url))
            return false;
        if (!$result = \util\file\write::to_filepath($filename, $response))
            return false;
        return true;
    }
}
