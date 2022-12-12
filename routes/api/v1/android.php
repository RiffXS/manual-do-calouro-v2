<?php

use App\Http\Response; 
use App\Controller\Api;

// ACTIVITY CADASTRO
$obRouter->post('/api/v1/android/cadastro', [
    'middlewares' => [
        'api',
    ],
    function($request) {
        return new Response(200, Api\Android::cadastroActivity($request), 'application/json');
    }
]);

// ACITIVY LOGIN
$obRouter->post('/api/v1/android/login', [
    'middlewares' => [
        'api',
    ],
    function($request) {
        return new Response(200, Api\Android::loginActivity($request), 'application/json');
    }
]);
