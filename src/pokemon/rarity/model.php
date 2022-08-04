<?php
namespace pokemon\rarity;

class model
{
    public static function get_list()
    {
        return table('PokemonRarity')->select()->query();
    }

    public static function get_by_id(int $id)
    {
        return table('PokemonRarity')->select()->where(['PokemonID' => $id])->query();
    }

    public static function get_by_rarity(string $rarity)
    {
        return table('PokemonRarity')->select()->where(['Rarity' => $rarity])->query();
    }
}
