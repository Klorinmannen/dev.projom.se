<?php

declare(strict_types=1);

namespace system;

class config
{
    private $_db = [];
    private $_system_dir = '';
    private $_src_dir = '';
    private $_conf_dir = '';

    public function __construct()
    {
        $this->_system_dir = str_replace('src/system', '', __DIR__);
        $this->_src_dir = $this->_system_dir . 'src/';
        $this->_conf_dir = $this->_system_dir . 'config/';
        $this->_db = self::load_db_config();
    }

    private function load_db_config(): array
    {
        return \util\json::parse($this->_conf_dir . 'db.json');
    }

    public function db(): array
    {
        return $this->_db;
    }
    public function api(): array
    {
        return $this->_api;
    }
    public function src_dir(): string
    {
        return $this->_src_dir;
    }
    public function system_dir(): string
    {
        return $this->_system_dir;
    }
    public function conf_dir(): string
    {
        return $this->_conf_dir;
    }

    public function dir(string $path): string
    {
        return $this->_system_dir . '/' . ltrim($path, '/');
    }
}
