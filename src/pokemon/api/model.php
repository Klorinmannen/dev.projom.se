<?php
namespace pokemon\api;

class model {
    public $_table = null;
    
    public function __construct() {
        $this->_table = new \util\table('Pokemon');
    }

    public function get_list(array $fields) {
        return $this->_table->select($fields)->where('Active <> 0')->query();
    }

    public function get_shiny_list(array $fields) {
        return $this->_table->select($fields)->where('Shiny <> 0')->query();
    }

    public function get_by_id(int $id, array $fields) {
        return $this->_table->select($fields)->where([ 'PokemonID' => $id ])->query();
    }

    public function get_by_name(string $name, array $fields) {
        return $this->_table->select($fields)->where([ 'Name' => $name ])->query();
    }

    public function add_new(array $data) {
        return $this->_table->insert($data)->query();
    }

    public function update_by_id(int $id, array $data) {
        return $this->_table->update($data)->where([ 'PokemonID' => $id ])->query();
    }
}
