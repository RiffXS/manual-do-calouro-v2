<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CALENDARIO
$obRouter->get('/calendar', [
    function() {
        return new Response(200, Pages\Calendar::getCalendar());
    }
]);
