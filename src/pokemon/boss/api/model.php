<?php
namespace pokemon\boss\api;

class model
{
    public $_table = null;

    public function __construct()
    {
        $this->_table = table('PokemonBoss')->join(['Pokemon',
                                                    'PokemonForm',
                                                    'PokemonBossTier'], 'INNER JOIN')->on(['PokemonID',
                                                                                           'PokemonFormID',
                                                                                           'PokemonBossTierID']);
    }

    public function get_list(array $fields)
    {
        return $this->_table->get($fields)->where('PokemonBoss.Active <> 0')->query();
    }

    public function get_shiny(array $fields)
    {
        return $this->_table->get($fields)->where('Pokemon.Shiny <> 0 AND PokemonBoss.Active <> 0')->query();
    }                                                    

    public function get_by_id(int $id, array $fields)
    {
        return $this->_table->get($fields)->where("PokemonBossID = $id AND PokemonBoss.Active <> 0")->query();
    }

    public function get_by_pokemon_id(int $id, array $fields)
    {
        return $this->_table->get($fields)->where("PokemonBoss.PokemonID = $id AND PokemonBoss.Active <> 0")->query();
    }

    public function update_by_id(int $id, array $data)
    {
        return $this->_table->update($data)->where(['PokemonBossID' => $id])->query();
    }

    public function add_new(array $data)
    {
        return $this->_table->insert($data)->query();
    }
}
