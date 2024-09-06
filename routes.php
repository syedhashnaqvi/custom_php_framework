<?php

$routes = [
    'GET' => [
        '/' => 'HomeController@index',
        '/contact' => 'ContactController@index',
        '/user/:id' => 'UserController@show',
    ],
    'POST' => [
        '/user/:id' => 'UserController@update',
    ]
];