<?php

declare(strict_types=1);

namespace api\route;

class auth
{
    public static function validate(array $auth_data): bool {

        if (!$auth_data['config_security'])
            return true;
        if (!$auth_data['route_security'])
            return true;
        
        if (!$jwt = $auth_data['auth_header'])
            return false;
        if (!\util\jwt::validate($jwt))
            return false;

        return true;
    }
}
