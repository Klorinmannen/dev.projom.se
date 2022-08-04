<?php
namespace pokemon\family\api;

class model {
    public $_table = null;
    
    public function __construct() {
        $this->_table = new \util\table('PokemonFamily');
    }

    public function get_list_by_pokemon_id(int $id) {
        return $this->_table->select([ 'PokemonFamilyID', 'PokemonID',
                                       'PokemonRelationID', 'RelativePokemonID' ])->where([ 'PokemonID' => $id ])->query();
    }
}
