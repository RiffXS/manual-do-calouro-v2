<?php 

use App\Http\Response;
use App\Controller\Admin;

// ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/admin/events', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Event::getEvents($request));
    }
]);

// ROTA DE FORMULARIO DE CADASTRO
$obRouter->get('/admin/events/new', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Event::getNewEvent($request));
    }
]);

// ROTA DE CADASTRO DE EVENTOS
$obRouter->post('/admin/events/new', [
    'middlewares' => [
        'admin-login'
    ],
    function ($request) {
        return new Response(200, Admin\Event::setNewEvent($request));
    } 
]);

// ROTA FORMULARIO DE EDIÇÃO DE EVENTO
$obRouter->get('/admin/events/edit/{id}', [
    'middlewares' => [
        'admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Event::getEditEvent($request, $id));
    }
]);

// ROTA DE ALTERAÇÃO DE EVENTO
$obRouter->post('/admin/events/edit/{id}', [
    'middlewares' => [
        'admin-login'
    ],
    function ($request, $id) {
        return new Response(200, Admin\Event::setEditEvent($request, $id));
    }
]);

// ROTA DE EXCLUSÃO DE EVENTO
$obRouter->post('/admin/events/del', [
    'middlewares' => [
        'admin-login'
    ],
    function ($request) {
        return new Response(200, Admin\Event::setDeleteEvent($request));
    }
]);