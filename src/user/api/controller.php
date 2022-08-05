<?php

declare(strict_types=1);

namespace user\api;

class controller extends \api\resource\controller
{

    public function login()
    {
        $user_data = $this->_json_data;

        if (!isset($user_data['username']) && !$user_data['username'])
            throw new \Exception('Missing username/password', 400);

        if (!isset($user_data['password']) && !$user_data['password'])
            throw new \Exception('Missing username/password', 400);

        if (!$user = $this->authenticate($user_data['username'], $user_data['password']))
            throw new \Exception('Failed to login', 400);

        $data['username'] = $user_data['username'];
        $data['jwt'] = \util\jwt::create($user['UserID'], $user['JWTKey']);

        return $data;
    }

    public function authenticate(
        string $username,
        string $password
    ): array {

        if (!$username || !$password)
            throw new \Exception('Missing username/password');

        if (!$record = $this->_resource_model->search_by_username($username))
            throw new \Exception('Invalid username/password');

        if (!\user\password::verify($password, $record['Password']))
            throw new \Exception('Invalid username/password');

        return $record;
    }
}
