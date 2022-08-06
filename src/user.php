<?php

declare(strict_types=1);

class user
{
    const SYSTEM_USER_ID = 1;

    // Default settings
    private $_user_name = 'Guest';
    private $_user_email = 'Guest';
    private $_user_id = 0;
    private $_page_id = 1;
    private $_user_file_path = '';
    private $_page_permission = [1, 2, 10];
    private $_status = false;
    private $_record = [];

    public function __construct(array $record = [])
    {
        self::from_record($record);
    }

    private function from_record(array $record): void
    {
        if (!$record)
            return;

        $this->_user_email = $record['Email'];
        $this->_user_name = $record['Username'];
        $this->_user_id = $record['UserID'];
        $this->_page_id = $record['PageID'];
        $this->_user_file_path = $record['FilePath'];
        $this->_page_permission = $record['PagePermission'];
        $this->_status = true;
        $this->_record = $record;
    }

    public function has_access(array $id_list = []): bool
    {
        if (!$id_list)
            return false;
        return (bool)array_intersect($id_list, $this->_page_permission);
    }

    public static function init(): void
    {
        $_SESSION['user'] = new \user();
    }
    public static function get(): object
    {
        return $_SESSION['user'];
    }

    public function set_user_email(string $user_email)
    {
        $this->_user_email = $user_email;
    }
    public function set_user_name(string $user_name)
    {
        $this->_user_name = $user_name;
    }
    public function set_user_id(int $user_id)
    {
        $this->_user_id = $user_id;
    }
    public function set_page_id(int $page_id)
    {
        $this->_page_id = $page_id;
    }

    public function get_user_file_path(): string
    {
        return $this->_user_file_path;
    }
    public function get_user_email(): string
    {
        return $this->_user_email;
    }
    public function get_user_name(): string
    {
        return $this->_user_name;
    }
    public function get_user_id(): int
    {
        return $this->_user_id;
    }
    public function get_page_id(): int
    {
        return $this->_page_id;
    }
    public function get_jwt_key(): string
    {
        return $this->_jwt_key;
    }
    public function get_status(): bool
    {
        return $this->_status;
    }
    public function get_page_permission(): array
    {
        return $this->_page_permission;
    }
}
