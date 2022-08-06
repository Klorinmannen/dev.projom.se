<?php

declare(strict_types=1);

namespace util;

class jwt
{
    public const EXPIRATION_SECONDS = (15 * 60) + 120;
    public const HASH_ALG = 'SHA256';
    public const KEY_BYTES = 30;
    public const ISSUER = 'dev.projom.se';

    public static function create(
        int $user_id,
        string $jwt_key
    ) {
        if (!$user_id || !$jwt_key)
            return false;

        if (!$base64url_header = static::get_header())
            return false;

        $issued_at = strtotime('now');
        if (!$base64url_payload = static::get_payload($issued_at, $user_id))
            return false;

        if (!$signature = static::generate_signature(static::HASH_ALG, $base64url_header, $base64url_payload, $jwt_key))
            return false;

        if (!$token = static::generate_token($base64url_header, $base64url_payload, $signature))
            return false;

        if (!static::validate_token_structure($token))
            return false;

        return $token;
    }

    private static function get_header(): string
    {
        $header = [
            'alg' => static::HASH_ALG,
            'typ' => 'JWT'
        ];

        return static::encode_base64url($header);
    }

    private static function get_payload(
        int $issued_at,
        int $user_id
    ): string {

        $payload = [
            'iss' => static::ISSUER,
            'sub' => $user_id,
            'iat' => $issued_at,
            'exp' => $issued_at + static::EXPIRATION_SECONDS
        ];

        return static::encode_base64url($payload);
    }

    private static function generate_signature(
        string $alg,
        string $base64url_header,
        string $base64url_payload,
        string $jwt_key
    ): string {
        $data = $base64url_header . '.' . $base64url_payload;
        return hash_hmac($alg, $data, $jwt_key);
    }

    private static function generate_token(
        string $base64url_header,
        string $base64url_payload,
        string $signature
    ): string {
        return $base64url_header . '.' . $base64url_payload . '.' . $signature;
    }

    private static function encode_base64url(array $data): string
    {
        if (!$data)
            return '';

        if (!$json_string = \util\json::encode($data))
            return '';

        return \util\base64::encode_url($json_string);
    }

    private static function validate_token_structure(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) != 3)
            return [];

        if (!$parts[0] || !$parts[1] || !$parts[2])
            return [];

        return $parts;
    }

    public static function validate(
        string $token,
        object $user
    ): void {

        if (!$token)
            throw new \Exception('Missing token', 401);

        if (!$parts = static::validate_token_structure($token))
            throw new \Exception('Malformed token', 401);

        $base64url_header = $parts[0];
        if (!$header = static::decode_base64url($base64url_header))
            throw new \Exception('Malformed token: header', 401);

        $base64url_payload = $parts[1];
        if (!$payload = static::decode_base64url($base64url_payload))
            throw new \Exception('Malformed token: payload', 401);

        $time = strtotime('now');
        static::validate_token($time, $payload, $header);

        $user_id = $user->get_user_id();
        $sub_user_id = (int)$payload['sub'];
        if ($user_id != $sub_user_id)
            throw new \Exception('Malformed token', 401);

        $table = new \util\table('User');
        if (!$user = $table->select('JWTKey')->where(['UserID' => $user_id])->query())
            throw new \Exception('Malformed token', 401);

        $user_jwt_key = $user['JWTKey'];
        $known_signature = static::generate_signature(static::HASH_ALG, $base64url_header, $base64url_payload, $user_jwt_key);
        $jwt_signature = $parts[2];

        if (!hash_equals($known_signature, $jwt_signature))
            throw new \Exception('Malformed token', 401);
    }

    private static function validate_token(
        int $time,
        array $payload,
        array $header
    ): void {

        if (empty($payload['exp']) || $time >= $payload['exp'])
            throw new \Exception('Token expired', 401);

        if (empty($header['alg']) || $header['alg'] != static::HASH_ALG)
            throw new \Exception('Malformed token', 401);

        if (empty($payload['sub']) || !\util\validate::id($payload['sub']))
            throw new \Exception('Malformed token', 401);
    }

    private static function decode_base64url(string $base64url): array
    {
        if (!$base64url)
            return [];

        if (!$json_string = \util\base64::decode_url($base64url))
            return [];

        return \util\json::decode($json_string);
    }

    public static function create_key(): string
    {
        return \util\base64::encode(random_bytes(30));
    }
}
