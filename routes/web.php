<?php

use CQ\Routing\Route;
use CQ\Routing\Middleware;
use CQ\Middleware\CORS;
use CQ\Middleware\JSON;
use CQ\Middleware\Session;
use CQ\Middleware\RateLimit;

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

Middleware::create(['prefix' => '/template', 'middleware' => [JSON::class, Session::class]], function () {
    Route::post('', 'TemplateController@create');
    Route::put('/{id}', 'TemplateController@update');
    Route::delete('/{id}', 'TemplateController@delete');

    Route::post('/{id}/key', 'TemplateController@createKey');
    Route::delete('/{id}/key', 'TemplateController@resetKey');
});

Middleware::create(['middleware' => [CORS::class, RateLimit::class, JWTMiddleware::class, JSON::class]], function () {
    Route::any('/submit', 'SubmissionController@submit');
});
