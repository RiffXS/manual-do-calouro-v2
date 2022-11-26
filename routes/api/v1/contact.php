<?php

use App\Http\Response; 
use App\Controller\Api;

// ROTA DADOS DE CONTATO
$obRouter->get('/api/v1/contact/data/{id}', [
    'middlewares' => [
        'api',
    ],
    function($request, $id) {
        return new Response(200, Api\Contact::getDataContact($request, $id), 'application/json');
    }
]);