<?php

return [
    'host' => getenv('SMTP_HOST'),
    'port' => getenv('SMTP_PORT'),
    'username' => getenv('SMTP_USER'),
    'password' => getenv('SMTP_PASSWORD'),
    'fromName' => 'Luca Castelnuovo',
];
