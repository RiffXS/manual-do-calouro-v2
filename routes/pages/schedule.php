<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA HORARIO
$obRouter->get('/schedule', [
    function($request) {
        return new Response(200, Pages\Schedule::getSchedule($request));
    }
]);

// ROTO DE CONSULTA DE SALA
$obRouter->post('/schedule', [
    function ($request) {
        return new Response(200, Pages\Schedule::getAvailability($request));
    }
]);
