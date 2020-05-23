<?php

namespace App\Helpers;

use Exception;
use CQ\JWT\JWT;
use CQ\Config\Config;

class JWTHelper
{
    /**
     * Create JWT provider
     *
     * @return JWT
     */
    private static function getProvider()
    {
        return new JWT([
            'iss' => Config::get('jwt.iss'),
            'aud' => Config::get('app.url'),
            'private_key' => Config::get('jwt.private_key'),
            'public_key' => Config::get('jwt.public_key')
        ]);
    }

    /**
     * Create JWT
     *
     * @param array $data
     * @param int $seconds_valid
     * @param string $aud
     * 
     * @return string
     */
    public static function create($data, $seconds_valid, $aud = null)
    {
        $provider = self::getProvider();

        return $provider->create($data, $seconds_valid, $aud);
    }

    /**
     * Validate JWT
     *
     * @param string $type
     * @param string $code
     * 
     * @return array
     * @throws Exception
     */
    public static function valid($type, $code)
    {
        $provider = self::getProvider();
        $claims = $provider->valid($code);

        if ($claims->type !== $type) {
            throw new Exception('Token type not valid');
        }

        return $claims;
    }
}
