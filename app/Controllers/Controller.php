<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;

class Controller
{
    public function getOrigin(ServerRequest $request)
    {
        return $request->getHeaders();
    }
}
