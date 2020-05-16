<?php

// TODO: simplify
// - set/check iss
// - set/check aud
// - discard setting/checking type

namespace App\Helpers;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class JWTHelper
{
    /**
     * Create JWT.
     *
     * @param string $type
     * @param array  $data
     * @param string $aud optional
     *
     * @return string
     */
    public static function create($type, $data, $aud = null)
    {
        $aud = $aud ?: config('jwt.iss');

        $head = [
            'iss' => config('jwt.iss'),
            'aud' => $aud,
            'iat' => time(),
            'exp' => time() + config('jwt')[$type],
            'type' => $type
        ];

        $payload = array_merge($head, $data);

        return JWT::encode(
            $payload,
            config('jwt.private_key'),
            config('jwt.algorithm')
        );
    }

    /**
     * Decode and validate JWT.
     *
     * @param string $type
     * @param string $token
     *
     * @return bool
     */
    public static function valid($type, $token)
    {
        if (!$token) {
            throw new Exception('Token not provided');
        }

        try {
            $credentials = JWT::decode(
                $token,
                config('jwt.secret'),
                [config('jwt.algorithm')]
            );
        } catch (ExpiredException $e) {
            throw new Exception('Token has expired');
        } catch (Exception $e) {
            throw new Exception('Token is invalid');
        }

        if ($type !== $credentials->type) {
            throw new Exception('Token type not valid');
        }

        return $credentials;
    }
}
