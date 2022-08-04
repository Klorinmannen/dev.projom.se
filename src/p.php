<?php
include('init.php');

if (true) {
    $table = new \util\table('Pokemon');
    $pokemon_list = $table->get('PokemonID')->query();
    $url = 'https://pokeapi.co/api/v2/pokemon-species/';

    foreach ($pokemon_list as $pokemon) {
        $pokemon_id = $pokemon['PokemonID'];
        $fetch_url = $url.$pokemon_id;
        if (!$result = \util\curl::get($fetch_url))
            continue;        
        if (!$decoded_list = \util\json::decode($result))
            continue;        
        if (!$flavor_list = $decoded_list['flavor_text_entries'])
            continue;
        if (!$en_list = array_filter($flavor_list, function ($flavor) { return $flavor['language']['name'] == 'en'; }))
            continue;
        $flavor = array_shift($en_list);
        $table->update([ 'FlavorText' => $flavor['flavor_text'] ])->where([ 'PokemonID' => $pokemon_id ])->query();
    }
}

if (false) {
    $table = new \util\table('Pokemon');
    $pokemon_list = $table->get('Name, DexID')->where('Alolan = 0 AND Galar = 0')->query();
    $url = 'https://img.pokemondb.net/artwork/';
    foreach ($pokemon_list as $pokemon) {
        $save_path = '/var/www/site/html/img/'.$pokemon['DexID'].'.jpg';
        $name = strtolower($pokemon['Name']);
        \util\curl::get_put($url.$name.'.jpg', $save_path);
    }    
}

if (false)
    exec('php /var/www/site/src/bg.php > /dev/null &');
if (false)
    \util\curl::get_put('https://pogoapi.net/api/v1/pokemon_evolutions.json', '/var/www/site/data/pokemon/pokemon_evo.json');
if (false)
    $conf = \api\config::get_referenced_config('user.yml');
if (false) 
    $user = \user\util::set_new_password(1, 'system');
if (false)
    $user = \user\util::create('system', 'system', true, true);

if (false) {
    $types = \util\json::parse('/var/www/site/data/pokemon/max_cp.json');
    $poke = table('Pokemon');
    foreach ($types as $pokemon) {
        if ($pokemon['form'] == 'Normal') {
            $poke->update(['MaxCombatPower_40' => $pokemon['max_cp']])->where(['PokemonID' => $pokemon['pokemon_id']])->query();
        }
    }
}

if (false) {
    $types = \util\json::parse('/var/www/site/data/pokemon/type_effectiveness.json');
    $poke_type = table('PokemonType');
    foreach ($types as $type => $multipliers)
        $poke_type->insert(['Type' => $type])->query();
}

if (false) {
    $types = \util\json::parse('/var/www/site/data/pokemon/type_effectiveness.json');
    $poke_type = table('PokemonType');
    $poke_type_eff = table('PokemonTypeEffectiveness');
    foreach ($types as $type => $multipliers) {
        $att_poke_type = $poke_type->select('PokemonTypeID')->where(['Type' => $type])->query();
        foreach ($multipliers as $type => $factor) {
            $deff_poke_type = $poke_type->select('PokemonTypeID')->where(['Type' => $type])->query();
            $fields = ['AttackingPokemonTypeID' => $att_poke_type['PokemonTypeID'],
                       'Multiplier' => $factor,
                       'DefendingPokemonTypeID' => $deff_poke_type['PokemonTypeID'] ];
            $poke_type_eff->insert($fields)->query();
        }
    }
    
}

if (false) {
    $rarities = \util\json::parse('/var/www/site/data/pokemon/rarity.json');
    $p_table = new \util\table('Pokemon');
    $r_table = new \util\table('PokemonRarity');
    foreach ($rarities as $rarity => $pokemons) {
        $rarity = $r_table->select('PokemonRarityID')->where(['Rarity' => $rarity])->query();
        foreach ($pokemons as  $pokemon)
            $p_table->update(['PokemonRarityID' => $rarity['PokemonRarityID']])->where(['DexID' => $pokemon['pokemon_id']])->query();
    }
}

if (false) {
    $forms = \util\json::parse('/var/www/site/data/pokemon/forms.json');
    $table = table('PokemonForm');
    foreach ($forms as $form)   
        $table->insert(['Form' => $form])->query();
}

if (false) {
    $pokemons = \util\json::parse('/var/www/site/data/pokemon/pokemons.json');
    $released_pokemons = \util\json::parse('/var/www/site/data/pokemon/released_pokemons.json');
    $ditto = \util\json::parse('/var/www/site/data/pokemon/ditto.json');
    $shiny = \util\json::parse('/var/www/site/data/pokemon/shiny_pokemons.json');
    $shadow = \util\json::parse('/var/www/site/data/pokemon/shadow_pokemons.json');
    $alolan = \util\json::parse('/var/www/site/data/pokemon/alolan.json');
    $galar = \util\json::parse('/var/www/site/data/pokemon/galarian.json');

    $special = new \util\table('PokemonSpecialForm');
    $table = new \util\table('Pokemon');
    $p = [];
    foreach ($pokemons as $p_id => $pokemon) {
        $fields = [];
        if (isset($ditto[$p_id]))
            $fields['Ditto'] = -1;
        if (isset($released_pokemons[$p_id]))
            $fields['Released'] = -1;
        if (isset($shiny[$p_id]))
            $fields['Shiny'] = -1;
        if (isset($shadow[$p_id]))
            $fields['Shadow'] = -1;
        if (isset($alolan[$p_id]))
            $fields['Alolan'] = -1;
        if (isset($galar[$p_id]))
            $fields['Galar'] = -1;

        $fields['Name'] = $pokemon['name'];
        if ($p_id >= 100)
            $fields['Description'] = '#'.$p_id;
        else
            $fields['Description'] = sprintf('#%03d', $p_id);

        $table->insert($fields)->query();

        if (isset($fields['Alolan']))            
            $special->insert([ 'Alolan' => -1, 'PokemonID' => $p_id ])->query();
        if (isset($fields['Galar']))
            $special->insert([ 'Galar' => -1, 'PokemonID' => $p_id ])->query();
    }

    $rarities = \util\json::parse('/var/www/site/data/pokemon/rarity.json');
    $r_table = new \util\table('PokemonRarity');
    foreach ($rarities as $rarity => $pokemons) {
        $rarity = $r_table->select('PokemonRarityID')->where(['Rarity' => $rarity])->query();
        foreach ($pokemons as  $pokemon)
            $table->update(['PokemonRarityID' => $rarity['PokemonRarityID']])->where(['PokemonID' => $pokemon['pokemon_id']])->query();
    }

    $generations = \util\json::parse('/var/www/site/data/pokemon/generations.json');
    foreach ($generations as $gen => $pokemons)
        foreach ($pokemons as  $pokemon)
            $table->update([ 'PokemonGenerationID' => $pokemon['generation_number'] ])->where([ 'PokemonID' => $pokemon['id'] ])->query();

    $types = \util\json::parse('/var/www/site/data/pokemon/types.json');
    $poke_type = new \util\table('PokemonType');
    $alolan = new \util\table('PokemonAlolan');
    $galarian = new \util\table('PokemonGalarian');
    foreach ($types as $pokemon) {

        if ($pokemon['form'] == 'Normal') {         
            if (isset($pokemon['type'][0])) {
                $type_id = $poke_type->select('PokemonTypeID')->where(['Type' => $pokemon['type'][0]])->query();
                $table->update(['PokemonTypeID1' => $type_id['PokemonTypeID']])->where(['PokemonID' => $pokemon['pokemon_id']])->query();
            }
            if (isset($pokemon['type'][1])) {
                $type_id = $poke_type->select('PokemonTypeID')->where(['Type' => $pokemon['type'][1]])->query();
                $table->update(['PokemonTypeID2' => $type_id['PokemonTypeID']])->where(['PokemonID' => $pokemon['pokemon_id']])->query();
            }
        }

        if ($pokemon['form'] == 'Alola'
            || $pokemon['form'] == 'Galarian' ) {
            
            if (isset($pokemon['type'][0])) {
                $type_id = $poke_type->select('PokemonTypeID')->where(['Type' => $pokemon['type'][0]])->query();
                $special->update(['PokemonTypeID1' => $type_id['PokemonTypeID']])->where(['PokemonID' => $pokemon['pokemon_id']])->query();
            }
            if (isset($pokemon['type'][1])) {
                $type_id = $poke_type->select('PokemonTypeID')->where(['Type' => $pokemon['type'][1]])->query();
                $special->update(['PokemonTypeID2' => $type_id['PokemonTypeID']])->where(['PokemonID' => $pokemon['pokemon_id']])->query();
            }
        }
    }
}

if (false) {
    $stat_list = \util\json::parse('/var/www/site/data/pokemon/stats.json');
    $table = new \util\table('Pokemon');

    foreach ($stat_list as $stat) {
        if ($stat['form'] == 'Normal')
            $table->update([ 'BaseAttack' => $stat['base_attack'],
                             'BaseDefense' => $stat['base_defense'],
                             'BaseStamina' => $stat['base_stamina'] ])->where([ 'PokemonID' => $stat['pokemon_id'] ])->query();
    }
}

if (false) {
    \util\curl::get_put('https://pogoapi.net/api/v1/pokemon_generations.json', '/var/www/site/data/pokemon/generations.json');
}
