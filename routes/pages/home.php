<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA HOME
$obRouter->get('/', [
    function($request) {
        return new Response(200, Pages\Home::getHome($request));
    }
]);

// ROTA CADASTRO DE COMENTARIO
$obRouter->post('/comment/new', [
    function($request) {
        return new Response(200, Pages\Home::setNewComment($request));
    }
]);