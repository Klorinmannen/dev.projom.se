<?php
declare(strict_types=1);
namespace common\dice\api;

class controller extends \api\resource\controller
{
    public const DEFAULT_FACES = 6;
    
    public function roll() {
        $faces = $this->_query_parameters['faces'] ?? static::DEFAULT_FACES;

        $value = 0;
        if ($faces)
            $value = \dice::roll((int)$faces);

        return [ 'value' => $value ];
    }    
}
