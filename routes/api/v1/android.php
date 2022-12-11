<?php

use App\Http\Response; 
use App\Controller\Api;

// ACTIVITY CADASTRO
$obRouter->get('/api/v1/android/cadastro', [
    'middlewares' => [
        'api',
    ],
    function($request) {
        return new Response(200, Api\Android::CadastroActivity($request), 'application/json');
    }
]);
