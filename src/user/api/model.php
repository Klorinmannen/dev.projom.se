<?php
declare(strict_types=1);

namespace user\api;

class model
{
    private $_table = null;

    public function __construct()
    {
        $this->_table = new \util\table('User');
    }

    public function get_user_by_email(string $email): array
    {
        return $this->_table->select()->where(['Email' => $email, 'Active' => -1])->query();
    }

    public function search_by_username(string $username): array
    {
        return $this->_table->select(['UserID', 'Password', 'Name', 'JWTKey'])->where(['Name' => $username, 'Active' => -1])->query();
    }

    public function insert(array $new_user)
    {
        return $this->_table->insert($new_user)->query();
    }

    public function get_jwt_key_by_user_id(int $user_id)
    {
        return $this->_table->select('JWTKey')->where(['UserID' => $user_id, 'Active' => -1])->query();
    }
}
