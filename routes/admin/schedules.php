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

// ROTA DE EDIÇÃO DE AULA
$obRouter->get('/admin/schedules/edit/{id}', [
    'middlewares' => [
        'admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Schedule::getEditSchedule($request, $id));
    }
]);