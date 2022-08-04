<?php
namespace pokemon\form;

class model
{
    public static function get_list(array $fields)
    {
        return table('PokemonForm')->select($fields)->where(['Active' => '-1'])->query();
    }

    public static function get_by_id(int $id, array $fields = [])
    {
        return table('PokemonForm')->select($fields)->where(['Active' => '-1',
                                                             'PokemonFormID' => $id])->query();
    }
}
