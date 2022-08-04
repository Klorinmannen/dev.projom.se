<?php
namespace pokemon\boss\api;

class controller extends \api\controller
{
    public const GET_FIELDS = [ 'PokemonBoss.PokemonID' => 'pokemon_id',
                                'PokemonForm.Form' => 'pokemon_form',
                                'PokemonBossTier.Name AS TierName' => 'pokemon_tier_name',
                                'PokemonBossTier.Tier' => 'pokemon_tier',
                                'MinCP' => 'normal_min_cp',
                                'MaxCP' => 'normal_max_cp',
                                'BoostedMinCP' => 'boosted_min_cp',
                                'BoostedMaxCP' => 'boosted_max_cp',
                                'Pokemon.Name' => 'pokemon_name',
                                'Pokemon.DexID' => 'pokemon_dex_number' ];

    public const FIELDS = [ 'PokemonID' => 'pokemon_id',
                            'PokemonBossTierID' => 'pokemon_boss_tier_id',
                            'PokemonFormID' => 'pokemon_form_id',
                            'MinCP' => 'min_cp',
                            'MaxCP' => 'max_cp',
                            'BoostedMinCP' => 'boosted_min_cp',
                            'BoostedMaxCP' => 'boosted_max_cp',
                            'Active' => 'active',
                            'Deleted' => 'deleted' ];

    public const REPLACE_FIELDS = [ '/^[\w]+\.[\w]+\s*AS\s*|[\w]+\./' => ''];

    public function get_get_fields()
    {
        return array_keys(static::GET_FIELDS);
    }

    public function get_by_id($id)
    {
        if (!validate_id($id))
            throw new \Exception('Missing/malformed id', 400);

        $pokemon_data = $this->_model->get_by_id($id, static::get_get_fields());
        return self::prepare_response($pokemon_data);
    }

    public function get_list()
    {
        $pokemon_data = $this->_model->get_list(self::get_get_fields());        
        return self::prepare_response($pokemon_data);
    }

    public function get_shiny_list()
    {
        $boss_data = $this->_model->get_shiny(self::get_get_fields());        
        return self::prepare_response($boss_data);
    }

    public function update()
    {
        $data = $this->_data;

        if (!$pokemon_boss_id = validate_array_key_id('pokemon_boss_id', $data))
            throw new \Exception('Missing pokemon boss id', 400);
        
        $new_data = [];
        foreach (static::FIELDS as $db_field => $code_field)
            if (isset($data[$code_field]))
                $new_data[$db_field] = $data[$code_field];

        if (!$new_data)
            return [];        
        if (!$rows_affected = $this->_model->update_by_id($pokemon_boss_id, $new_data))
            return [];

        $boss = $this->_model->get_by_id($pokemon_boss_id, static::GET_FIELDS);
        return self::prepare_response($boss);
    }

    public function insert()
    {
        $data = $this->_data;

        if (!$pokemon_id = validate_array_key_id('pokemon_id', $data))
            throw new \Exception('Missing pokemon boss id', 400);

        if ($pokemon_boss_exists = $this->_model->get_by_pokemon_id($pokemon_id, static::GET_FIELDS))
            throw new \Exception('Pokemon boss is already in database, did you mean to patch?', 400);
        
        if (!$pokemon_boss_tier_id = validate_array_key_id('pokemon_boss_tier_id', $data))
            throw new \Exception('Missing pokemon boss tier id', 400);

        if (!$pokemon_form_id = validate_array_key_id('pokemon_form_id', $data))
            throw new \Exception('Missing pokemon boss form id', 400);
        
        $new_data = [];
        foreach (static::FIELDS as $db_field => $code_field)
            if (isset($data[$code_field]))
                $new_data[$db_field] = $data[$code_field];

        if (!$new_data)
            return [];
        if (!$pokemon_boss_id = $this->_model->add_new($pokemon_boss_id, $new_data))
            return [];
                
        $boss = $this->_model->get_by_id($pokemon_boss_id, static::GET_FIELDS);
        return self::prepare_response($boss);
    }
    
    public function prepare_response($data)
    {
        if (!$data)
            throw new \Exception('No pokemon boss found', 404);
        
        $response_data = [];
        if (isset($data[0]))
            foreach ($data as $pokemon)
                $response_data[] = self::format_response($pokemon);
        else
            $response_data = self::format_response($data);

        return $response_data;
    }
    
    public function format_response($pokemon)
    {
        $response = [];
        $pattern = key(static::REPLACE_FIELDS);
        $replace_with = current(static::REPLACE_FIELDS);            
        foreach (static::GET_FIELDS as $db_field => $code_field) {
            $db_field = preg_replace($pattern, $replace_with, $db_field);
            $response[$code_field] = $pokemon[$db_field];
        }
        return $response;
    }
}
