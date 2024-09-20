<?php

$routes = [
    'GET' => [
        '/' => 'HomeController@index',
        '/register' => 'AuthController@register',
        '/login' => 'AuthController@login',
        '/contact' => 'ContactController@index',
        '/user/:id' => 'UserController@show',
    ],
    'POST' => [
        '/register' => 'AuthController@store',
        '/login' => 'AuthController@verifyLogin',
        '/user/:id' => 'UserController@update',
    ]
];