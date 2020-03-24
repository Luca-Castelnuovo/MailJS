<?php

require_once __DIR__.'/../vendor/autoload.php';

require 'config.php';
require 'database.php';
require 'router.php';

session_start();

return $router;
