<?php

$config = config('database');

$database = new \Medoo\Medoo([
    'database_type' => 'mysql',
    'server' => $config['host'],
    'port' => $config['port'],
    'database_name' => $config['database'],
    'username' => $config['username'],
    'password' => $config['password']
]);
