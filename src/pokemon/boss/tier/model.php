<?php
namespace pokemon\boss\tier;

class model
{
    public static function get_list(array $fields)
    {
        return table('PokemonBossTier')->select($fields)->where(['Active' => '-1'])->query();
    }

    public static function get_by_id(int $id,array $fields = [])
    {
        return table('PokemonBossTier')->select($fields)->where(['Deleted' => '0',
                                                                 'PokemonBossTierID' => $id])->query();
    }
}
