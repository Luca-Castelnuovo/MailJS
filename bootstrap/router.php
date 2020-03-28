<?php

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Zend\Diactoros\Response\RedirectResponse;

use App\Middleware\CORSMiddleware;
use App\Middleware\JSONMiddleware;
use App\Middleware\JWTMiddleware;
use App\Middleware\SessionMiddleware;

$router = new Router('', 'App\Controllers');
$router->define('code', '[0-9]+');

$router->get('/', 'GeneralController@index');
$router->get('/dashboard', 'GeneralController@dashboard', SessionMiddleware::class);
$router->get('/error/{code}', 'GeneralController@error');

$router->get('/auth/login', 'AuthController@login');
$router->get('/auth/callback', 'AuthController@callback');
$router->get('/auth/logout', 'AuthController@logout');

$router->group(['middleware' => JSONMiddleware::class, SessionMiddleware::class], function (Router $router) {
    $router->post('/template', 'TemplateController@create');
    $router->put('/template/{id}', 'TemplateController@update');
    $router->delete('/template/{id}', 'TemplateController@delete');
});

$router->group(['middleware' => [CORSMiddleware::class, JSONMiddleware::class, JWTMiddleware::class]], function (Router $router) {
    $router->post('/submission/json', 'SubmissionController@json');
    $router->post('/submission/form', 'SubmissionController@form');
});


try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    $router->getPublisher()->publish(new RedirectResponse('/error/404'));
} catch (Throwable $e) {
    // TODO: add logger for 500 errors
    $router->getPublisher()->publish(new RedirectResponse("/error/500?e={$e}"));
}
