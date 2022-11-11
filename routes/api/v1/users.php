<?php

use App\Http\Response; 
use App\Controller\Api;

// ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/api/v1/testimonies', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function($request) {
        return new Response(200, Api\User::getUsers($request), 'application/json');
    }
]);

// ROTA CONSULTA INDIVIDUAL DE DEPOIMENTOS
$obRouter->get('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function($request, $id) {
        return new Response(200, Api\User::getUser($request, $id), 'application/json');
    }
]);

// ROTA CADASTRO DE DEPOIMENTOS
$obRouter->post('/api/v1/testimonies', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request) {
        return new Response(201, Api\User::setNewUser($request), 'application/json');
    }
]);

// ROTA ATUALIZAÇÃO DE DEPOIMENTOS
$obRouter->put('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::setEditUser($request, $id), 'application/json');
    }
]);

// ROTA CADASSTRO DE DEPOIMENTOS
$obRouter->delete('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::setDeleteUser($request, $id), 'application/json');
    }
]);

// ROTA USUARIO CONCTADO
$obRouter->get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request) {
        return new Response(200, Api\User::getCurrentUser($request), 'application/json');
    }
]);
