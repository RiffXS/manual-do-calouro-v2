<?php

use App\Http\Response; 
use App\Controller\Api;

// ROTA DE LISTAGEM DE USUARIOS
$obRouter->get('/api/v1/users', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request) {
        return new Response(200, Api\User::getUsers($request), 'application/json');
    }
]);

// ROTA USUARIO CONCTADO
$obRouter->get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request) {
        return new Response(200, Api\User::getCurrentUser($request), 'application/json');
    }
]);

// ROTA CONSULTA INDIVIDUAL DE USUARIOS
$obRouter->get('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::getUser($request, $id), 'application/json');
    }
]);

// ROTA CADASTRO DE USUARIOS
$obRouter->post('/api/v1/users/new', [
    'middlewares' => [
        'api'
    ],
    function($request) {
        return new Response(201, Api\User::setNewUser($request), 'application/json');
    }
]);

// ROTA ATUALIZAÇÃO DE USUARIO
$obRouter->put('/api/v1/users/edit/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::setEditUser($request, $id), 'application/json');
    }
]);

// ROTA EXCLUIR UM USUARIO
$obRouter->delete('/api/v1/users/delete/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::setDeleteUser($request, $id), 'application/json');
    }
]);
