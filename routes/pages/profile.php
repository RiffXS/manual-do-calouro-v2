<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA PERFIL
$obRouter->get('/profile', [
    'middlewares' => [
        'user-login'
    ],
    function($request) {
        return new Response(200, Pages\Profile::getEditProfile($request));
    }
]);

// ROTA PERFIL
$obRouter->post('/profile', [
    'middlewares' => [
        'user-login'
    ],
    function($request) {
        return new Response(200, Pages\Profile::setEditProfile($request));
    }
]);