<?php

use MiladRahimi\PhpRouter\Router;
use App\Middleware\AuthenticationMiddleware;

$router = new Router('', 'App\Controllers');

$router->get('/', 'GeneralController@index');

$router->group(['middleware' => AuthenticationMiddleware::class], function (Router $router) {
    $router->get('/submission/json', 'SubmissionController@json');
    $router->post('/submission/json', 'SubmissionController@json');
    $router->post('/submission/form', 'SubmissionController@form');
});

