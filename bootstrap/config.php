<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// TODO: clean function config('auth.client_id')
function config($key, $fallback = null)
{
    static $config;

    if (is_null($config)) {
        $configExternal = json_decode(file_get_contents(env('EXTERNAL_CONFIG')));
        $config = [
            'captcha_endpoint' => 'https://www.google.com/recaptcha/api/siteverify',
            'analytics' => [
                'enabled' => false,
                'ackee_domainid' => '',
                'ackee_options' => '{ "detailed": true }'
            ],
            'auth' => [
                'client_id' => env('GITHUB_CIENT_ID'),
                'client_secret' => env('GITHUB_CLIENT_SECRET'),
                // 'redirect_url' => 'https://mail.lucacastelnuovo.nl/auth/callback',
                'redirect_url' => 'http://localhost:8080/auth/callback',
                'allowed_users' => $configExternal->allowed_users,
                'session_expires' => 1800 // 30 min
            ],
            'cors' => [
                'allow_origins' => ['*'],
                'allow_headers' => ['Authorization', 'Content-Type'],
                'allow_methods' => ['HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS']
            ],
            'database' => [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD')
            ],
            'hmac' => [
                'algorithm' => 'sha256',
                'secret' => env('APP_KEY')
            ],
            'jwt' => [
                'iss' => env('APP_URL'),
                'ttl' => 31536000, // 1 year
                'algorithm' => 'HS256',
                'secret' => env('APP_KEY')
            ],
            'sentry' => [
                'dsn' => 'https://20ba7ab4da6941e5b8daf9e51b3791af@sentry.io/5178923'
            ],
            'smtp' => [
                'host' => env('SMTP_HOST'),
                'port' => env('SMTP_PORT'),
                'username' => env('SMTP_USER'),
                'password' => env('SMTP_PASSWORD'),
                'fromName' => 'Notification - Luca Castelnuovo'
            ]
        ];
    }

    return array_key_exists($key, $config) ? $config[$key] : $fallback;
}
