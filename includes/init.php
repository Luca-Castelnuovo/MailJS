<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

require 'config.php';
require 'auth.php';
require 'files.php';
require 'output.php';
require 'projects.php';
require 'security.php';
require 'sql.php';
require 'template.php';

// External
require '/var/www/cdn.lucacastelnuovo.nl/include.php';
