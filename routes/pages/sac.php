<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA DA PAGINA FALE CONOSCO
$obRouter->get('/sac', [
    'middlewares' => [
        'user-login'
    ],
    function($request) {
        return new Response(200, Pages\Sac::getSac($request));
    }
]);

// ROTA DO FORMULARIO DE FALE CONOSCO
$obRouter->post('/sac', [
    'middlewares' => [
        'user-login'
    ],
    function($request) {
        return new Response(200, Pages\Sac::setSac($request));
    }
]);