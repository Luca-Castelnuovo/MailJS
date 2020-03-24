<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\HtmlResponse;

class GeneralController extends Controller
{
    /**
     * Login screen
     *
     * @return HtmlResponse
     */
    public function index()
    {
        return new HtmlResponse($this->twig->render('index.twig', ['name' => 'Fabien']), 200);
    }

    /**
     * Dashboard screen
     *
     * @return HtmlResponse
     */
    public function dashboard(ServerRequest $request)
    {
        /*
        
            1. get user id from session
            2. query templates belonging to user
            3. render templates
        
        */

        return new HtmlResponse($this->twig->render('dashboard.twig', ['name' => 'Fabien']), 200);
    }
}
