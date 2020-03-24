<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;

class Controller
{
    public $twig;

    public function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->twig = new \Twig\Environment($loader);
        // $this->twig = new \Twig\Environment($loader, [
        //     'cache' => '../storage/views',
        // ]);
    }
}
