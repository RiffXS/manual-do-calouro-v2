<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA DE CONFIGURAÇÕES
$obRouter->get('/config', [
    'middlewares' => [
        'user-login'
    ],
    function($request) {
        return new Response(200, Pages\Config::getConfig($request));
    }
]);

// ROTA DE REDEFINIR SENHA
$obRouter->post('/config/redefine', [
    'middlewares' => [
        'user-login'
    ],
    function ($request) {
        return new Response(200, Pages\Config::setNewPassword($request));
    }
]);

// ROTA DE EXCLUSÃO
$obRouter->post('/config/delete', [
    'middlewares' => [
        'user-login'
    ],
    function($request) {
        return new Response(200, Pages\Config::setDeleteAcount($request));
    }
]);