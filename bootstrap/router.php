<?php

use MiladRahimi\PhpRouter\Router;
use App\Middleware\CORSMiddleware;
use App\Middleware\AuthenticationMiddleware;

$router = new Router('', 'App\Controllers');


$router->get('/', 'GeneralController@index');

$router->get('/auth/login', 'AuthController@login');
$router->get('/auth/callback', 'AuthController@callback');
$router->get('/auth/logout', 'AuthController@logout');


$router->group(['middleware' => CORSMiddleware::class], function (Router $router) {
    $router->group(['middleware' => AuthenticationMiddleware::class], function (Router $router) {
        $router->post('/submission/json', 'SubmissionController@json');
        $router->post('/submission/form', 'SubmissionController@form');
    });
});

