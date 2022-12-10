<?php

use App\Http\Response; 
use App\Controller\Api;

// ROTA DE CONSULTA DE DEPOIMENTO
$obRouter->get('/admin/comments/view/{id}', [
    'middlewares' => [
        'api',
    ],
    function($request, $id) {
        return new Response(200, Api\Comment::getViewComment($request, $id), 'application/json');
    }
]);