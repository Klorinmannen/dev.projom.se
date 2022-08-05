<?php

declare(strict_types=1);

namespace user;

class password
{
    public const HASH_ALG = 'SHA256';
    public const SERVER_SECRET = 'iOYoJQ+qldvTZDCSweGJP/p8YAU=';

    public static function create(string $new_password = ''): string
    {
        if (!$new_password)
            $new_password = static::generate();
        return static::hash($new_password);
    }

    public static function generate(int $bytes = 10): string
    {
        $pseudo_bytes = openssl_random_pseudo_bytes($bytes);
        return bin2hex($pseudo_bytes);
    }

    public static function verify(
        string $password,
        string $stored_password
    ): bool {
        $hashed_password = static::hash_with_secret($password);
        return password_verify($hashed_password, $stored_password);
    }

    public static function hash(string $password): string
    {
        $hashed_password = static::hash_with_secret($password);
        return password_hash($hashed_password, \PASSWORD_DEFAULT);
    }

    private static function hash_with_secret(string $password): string
    {
        return hash_hmac(static::HASH_ALG, $password, static::SERVER_SECRET);
    }

    public static function validate(string $password): array
    {
        $error_list = [];
        if (!\util\strings::match_pattern($password, '/.{8,}/'))
            $error_list[] = 'Password has to be atleast 8 characters long';
        if (!\util\strings::match_pattern($password, '/[0-9]+/'))
            $error_list[] = 'Password has to contain atleast 1 number';
        if (!\util\strings::match_pattern($password, '/[a-z]+/'))
            $error_list[] = 'Password has to contain atleast 1 lower case character';
        if (!\util\strings::match_pattern($password, '/[A-Z]+/'))
            $error_list[] = 'Password has to contain atleast 1 upper case character';
        if (!\util\strings::match_pattern($password, '/[^\w]+|[_]+/'))
            $error_list[] = 'Password has to contain atleast 1 special character';
        return $error_list;
    }
}
