<?php

return [
    'algorithm' => 'RS256',
    'private_key' => str_replace('||||', PHP_EOL, getenv('JWT_PRIVATE_KEY')),
    'public_key' => str_replace('||||', PHP_EOL, getenv('JWT_PUBLIC_KEY')),
    'iss' => getenv('APP_URL'),
    'submission' => 31536000 // 1year
];
