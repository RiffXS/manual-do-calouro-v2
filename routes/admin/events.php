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

// ROTA DE CADASTRO DE EVENTO
$obRouter->get('/admin/events/new', [
    'middlewares' => [
        'admin-login'
    ],
    function ($request) {
        return new Response(200, Admin\Event::getNewEvent($request));
    }
]);