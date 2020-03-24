<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

function config($key, $fallback = null) {
    static $config;
    
    if (is_null($config)) {
        $configExternal = json_decode(file_get_contents(env('EXTERNAL_CONFIG')));
        $config = [
            'allowed_users' => $configExternal->allowed_users,
            'captcha_endpoint' => 'https://www.google.com/recaptcha/api/siteverify',
            'database' => [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
            ],
            'jwt' => [
                'algorithm' => 'HS256',
                'iss' => env('JWT_ISS'),
                'length' => 64,
                'ttl' => 31536000, // 1 year
                'secret' => env('JWT_SECRET'),
            ],
            'smtp' => [
                'host' => env('SMTP_HOST'),
                'port' => env('SMTP_PORT'),
                'username' => env('SMTP_USER'),
                'password' => env('SMTP_PASSWORD'),
                'ssl' => env('SMTP_USESSL'),
            ],
            'cors' => [
                'allow_origins' => ['*'],
                'allow_headers' => ['Authorization', 'Content-Type'],
                'allow_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
            ],
        ];
    }
    
    return array_key_exists($key, $config) ? $config[$key] : $fallback;
}
