<?php

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Zend\Diactoros\Response\RedirectResponse;
use App\Middleware\CORSMiddleware;
use App\Middleware\AuthMiddleware;

$router = new Router('', 'App\Controllers');

$router->get('/', 'GeneralController@index');
$router->get('/dashboard', 'GeneralController@dashboard');

$router->define('code', '[0-9]+');
$router->get('/error/{code}', 'GeneralController@error');

$router->get('/auth/login', 'AuthController@login');
$router->get('/auth/callback', 'AuthController@callback');
$router->get('/auth/logout', 'AuthController@logout');

$router->group(['middleware' => CORSMiddleware::class], function (Router $router) {
    $router->group(['middleware' => AuthMiddleware::class], function (Router $router) {
        $router->post('/submission/json', 'SubmissionController@json');
        $router->post('/submission/form', 'SubmissionController@form');
    });
});


try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    $router->getPublisher()->publish(new RedirectResponse('/error/404'));
} catch (Throwable $e) {
    // TODO: add logger for 500 errors
    $router->getPublisher()->publish(new RedirectResponse('/error/500'));
}
