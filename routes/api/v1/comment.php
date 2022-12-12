<?php

use App\Http\Response; 
use App\Controller\Api;

// ROTA DE CONSULTA DE DEPOIMENTO
$obRouter->get('/api/v1/comments/view/{id}', [
    'middlewares' => [
        'api',
    ],
    function($request, $id) {
        return new Response(200, Api\Comment::getViewComment($request, $id), 'application/json');
    }
]);