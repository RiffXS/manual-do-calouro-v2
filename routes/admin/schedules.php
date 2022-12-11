<?php

use App\Http\Response;
use App\Controller\Admin;

// ROTA DE LISTAGEM DE AULAS
$obRouter->get('/admin/schedules', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Schedule::getSchedules($request));
    }
]);

// ROTA DE CADASTRO DE AULA
$obRouter->get('/admin/schedules/new', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Schedule::getNewSchedule($request));
    }
]);

// ROTA DE FORMULARIO DE CADASTRO
$obRouter->post('/admin/schedules/new', [
    'middlewares' => [
        'admin-login'
    ],
    function ($request) {
        return new Response(200, Admin\Schedule::setNewSchedule($request));
    }
]);

// ROTA DE EDIÇÃO DE AULA
$obRouter->get('/admin/schedules/edit/{id}', [
    'middlewares' => [
        'admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Schedule::getEditSchedule($request, $id));
    }
]);

// ROTA DE ALTERAÇÃO DE AULA
$obRouter->post('/admin/schedules/edit/{id}', [
    'middlewares' => [
        'admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Schedule::setEditSchedule($request, $id));
    } 
]);

// ROTA DE EXCLUSÃO DE AULA
$obRouter->post('/admin/schedules/del', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Schedule::setDeleteSchedule($request));
    } 
]);