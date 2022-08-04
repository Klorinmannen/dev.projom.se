<?php
namespace util;

class jwt {
    public const EXPIRATION_SECONDS = (15 * 60) + 120;
    public const HASH_ALG = 'SHA256';
    
    public static function create(int $user_id, string $jwt_key) {
        if (!$user_id || !$jwt_key)
            return false;
        if (!$base64url_header = static::get_header())
            return false;
        if (!$base64url_payload = static::get_payload($user_id))
            return false;
        if (!$signature = static::generate_signature(static::HASH_ALG, $base64url_header, $base64url_payload, $jwt_key))
            return false;
        if (!$token = static::generate_token($base64url_header, $base64url_payload, $signature))
            return false;
        if (!static::validate_token_structure($token))
            return false;
        return $token;
    }

    private static function get_header() {
        $header = [ 'alg' => 'SHA256',
                    'typ' => 'JWT' ];
        return static::encode_base64url($header);
    }

    private static function get_payload(int $user_id) {
        $issued_at = strtotime('now');
        $payload = [ 'iss' => 'www.projom.se',
                     'sub' => $user_id,
                     'iat' => $issued_at,
                     'exp' => $issued_at + static::EXPIRATION_SECONDS ];
        return static::encode_base64url($payload);
    }

    private static function generate_signature(string $alg, string $base64url_header, string $base64url_payload, string $jwt_key) {
        $data = $base64url_header.'.'.$base64url_payload;
        return hash_hmac($alg, $data, $jwt_key);
    }

    private static function generate_token(string $base64url_header, string $base64url_payload, string $signature) {
        return $base64url_header.'.'.$base64url_payload.'.'.$signature;
    }

    private static function encode_base64url(array $data) {
        if (!$data)
            return false;
        if (!$json_string = \util\json::encode($data))
            return false;
        return \util\base64::encode_url($json_string);
    }

    private static function validate_token_structure(string $token) {
        $parts = explode('.', $token);
        if (count($parts) != 3)
            return false;
        if (!$parts[0] || !$parts[1] || !$parts[2])
            return false;
        return $parts;
    }

    public static function validate(string $token) {
        if (!$parts = static::validate_token_structure($token))
            return false;

        $base64url_header = $parts[0];
        $header = static::decode_base64url($base64url_header);
        $base64url_payload = $parts[1];
        $payload = static::decode_base64url($base64url_payload);

        static::validate_token($payload, $header);

        $table = new \util\table('User');
        if (!$record = $table->select('JWTKey')->where([ 'UserID' => $payload['sub'] ])->query())
            return false;

        $jwt_signature = $parts[2];
        $known_signature = static::generate_signature(static::HASH_ALG, $base64url_header, $base64url_payload, $record['JWTKey']);
        return hash_equals($known_signature, $jwt_signature);
    }

    private static function validate_token(array $payload, array $header) {
        $time = strtotime('now');
        if (empty($payload['exp']) || $time >= $payload['exp'])
            throw new \Exception('Token expired', 401);
        if (empty($header['alg']) || $header['alg'] != static::HASH_ALG)
            throw new \Exception('Malformed token', 401);
        if (empty($payload['sub']) || !\util\validate::id($payload['sub']))
            throw new \Exception('Malformed token', 401);
    }

    private static function decode_base64url(string $base64url) {
        if (!$base64url)
            return false;
        if (!$json_string = \util\base64::decode_url($base64url))
            return false;
        return \util\json::decode($json_string);
    }

    public static function create_key() {
        return \util\base64::encode(random_bytes(30));
    }
}
