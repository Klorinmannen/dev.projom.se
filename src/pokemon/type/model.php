<?php
namespace pokemon\type;

class model
{
    public static function get_list()
    {
        return table('PokemonType')->select()->query();
    }

    public static function get_by_id(int $id)
    {
        return table('PokemonType')->select()->where(['PokemonID' => $id])->query();
    }

    public static function get_by_type(string $type)
    {
        return table('PokemonType')->select()->where(['Type' => $type])->query();
    }
}
