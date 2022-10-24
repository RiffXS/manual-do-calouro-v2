<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA HORARIO
$obRouter->get('/schedule', [
    function($request) {
        return new Response(200, Pages\Schedule::getSchedule($request));
    }
]);
