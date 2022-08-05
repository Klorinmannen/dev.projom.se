<?php

declare(strict_types=1);

namespace user;

class util
{
    public static function add_new_user(
        string $username,
        string $password
    ): void {

        if (!\util\validate::string($username))
            throw new \Exception('Missing username', 400);
        if (!\util\validate::string($password))
            throw new \Exception('Missing password', 400);

        if ($record = \user\model::search_by_username($username))
            throw new \Exception('Failed to create user', 400);

        \user\password::validate($password);

        static::create($username, $password, false, true);
    }

    public static function create(
        string $username,
        string $password,
        bool $api_user = false,
        bool $activate = false
    ): array {

        $user['Name'] = $username;
        $user['Password'] = \user\password::hash($password);
        $user['Active'] = $activate ? -1 : 0;

        if ($api_user)
            $user['JWTKey'] = \util\jwt::create_key();

        return \user\model::add($user);
    }

    public static function set_new_api_key(int $user_id): string
    {
        if (!\util\validate::id($user_id))
            throw new \Exception('Missing/malformed user id', 400);

        $jwt_key = \util\jwt::create_key();
        \user\model::update_user($user_id, ['JWTKey' => $jwt_key]);

        return $jwt_key;
    }

    public static function set_new_password(
        int $user_id,
        string $new_password = ''
    ): array {
        if (!\util\validate::id($user_id))
            throw new \Exception('Missing/malformed user id', 400);

        $new_password = \user\password::create($new_password);
        \user\model::update_user($user_id, ['Password' => $new_password]);

        return \user\model::get_user($user_id);
    }

    public static function redirect(string $url): void
    {
        header('Location: '.$url);
        exit;
    }
}
