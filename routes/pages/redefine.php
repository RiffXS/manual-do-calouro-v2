<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA DA PAGINA DE REDEFINIR SENHA
$obRouter->get('/redefine', [
    function($request) {
        return new Response(200, Pages\Redefine::getRedefine($request));
    }
]);

// ROTA FROMULARIO REDEFINIR SENHA
$obRouter->post('/redefine', [
    function($request) {
        return new Response(200, Pages\Redefine::setRedefine($request));
    }
]);
