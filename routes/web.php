<?php

use App\Middleware\JWTMiddleware;
use CQ\Middleware\CORS;
use CQ\Middleware\JSON;
use CQ\Middleware\RateLimit;
use CQ\Middleware\Session;
use CQ\Routing\Middleware;
use CQ\Routing\Route;

Route::$router = $router->get();
Middleware::$router = $router->get();

Route::get('/', 'GeneralController@index');
Route::get('/error/{code}', 'GeneralController@error');

Middleware::create(['prefix' => '/auth'], function () {
    Route::get('/request', 'AuthController@request');
    Route::get('/callback', 'AuthController@callback');
    Route::get('/logout', 'AuthController@logout');
});

Middleware::create(['middleware' => [Session::class]], function () {
    Route::get('/dashboard', 'UserController@dashboard');
    Route::get('/history/{id}', 'UserController@history');
});

Middleware::create(['prefix' => '/template', 'middleware' => [Session::class]], function () {
    Route::post('', 'TemplateController@create', JSON::class);
    Route::put('/{id}', 'TemplateController@update', JSON::class);
    Route::delete('/{id}', 'TemplateController@delete');

    Route::post('/{id}/key', 'TemplateController@createKey', JSON::class);
    Route::delete('/{id}/key', 'TemplateController@resetKey');
});

Middleware::create(['middleware' => [CORS::class, RateLimit::class, JWTMiddleware::class, JSON::class]], function () {
    Route::any('/submit', 'SubmissionController@submit');
});
