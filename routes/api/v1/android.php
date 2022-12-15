<?php

use App\Http\Response; 
use App\Controller\Api;

// ACTIVITY CADASTRO
$obRouter->post('/api/v1/android/cadastro', [
    'middlewares' => [
        'api',
    ],
    function($request) {
        return new Response(200, Api\Android::signUpActivity($request), 'application/json');
    }
]);

// ACITIVY LOGIN
$obRouter->post('/api/v1/android/login', [
    'middlewares' => [
        'api',
    ],
    function($request) {
        return new Response(200, Api\Android::singInActivity($request), 'application/json');
    }
]);

// ACTIVITY HORARIO
$obRouter->get('/api/v1/android/horario', [
    'middlewares' => [
        'api'
    ],
    function($request) {
        return new Response(200, Api\Android::scheduleActivity($request), 'application/json');
    }
]);

// ACTIVITY CONTATOS
$obRouter->get('/api/v1/android/contatos', [
    'middlewares' => [
        'api'
    ],
    function($request) {
        return new Response(200, Api\Android::contactsActivity($request), 'application/json');
    }
]);