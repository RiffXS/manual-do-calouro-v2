<?php 

use App\Http\Response;
use App\Controller\Pages;

// ROTA HOME
$obRouter->get('/', [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

// ROTA SOBRE
$obRouter->get('/about', [
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);

// ROTA CALENDARIO
$obRouter->get('/calendar', [
    function() {
        return new Response(200, Pages\Calendar::getCalendar());
    }
]);

// ROTA MAPA
$obRouter->get('/map', [
    function() {
        return new Response(200, Pages\Map::getMap());
    }
]);


// ROTA HORARIO
$obRouter->get('/schedule', [
    function() {
        return new Response(200, Pages\Schedule::getSchedule());
    }
]);

/*
// ROTA DE DEPOIMENTOS
$obRouter->get('/depoimentos', [
    'middlewares' => [
        'cache'
    ],
    function($request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

// ROTA DE DEPOIMENTOS INSERT
$obRouter->post('/depoimentos', [
    function($request) {
        return new Response(200, Pages\Testimony::inserTestimony($request));
    }
]);
*/