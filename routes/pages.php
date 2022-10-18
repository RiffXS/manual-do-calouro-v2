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
    function($request) {
        return new Response(200, Pages\Schedule::getSchedule($request));
    }
]);

// ROTA CONTATO
$obRouter->get('/contact', [
    function() {
        return new Response(200, Pages\Contact::getContact());
    }
]);

// ROTA ROD
$obRouter->get('/rod', [
    function() {
        return new Response(200, Pages\Rod::getRod());
    }
]);

// ROTA FAQ
$obRouter->get('/faq', [
    function() {
        return new Response(200, Pages\Faq::getFaq());
    }
]);

// ROTA LOGIN
$obRouter->get('/singin', [
    function($request) {
        return new Response(200, Pages\Singin::getSingin($request));
    }
]);

// ROTA LOGIN
$obRouter->post('/singin', [
    function($request) {
        return new Response(200, Pages\SingIn::setSingIn($request));
    }
]);

// ROTA CADASTRO
$obRouter->get('/singup', [
    function($request) {
        return new Response(200, Pages\SingUp::getSingUp($request));
    }
]);

// ROTA CADASTRO
$obRouter->post('/singup', [
    function($request) {
        return new Response(200, Pages\SingUp::setSingUp($request));
    }
]);

// ROTA RECUPERAR SENHA
$obRouter->get('/recovery', [
    function() {
        return new Response(200, Pages\Recovery::getRecovery());
    }
]);

// ROTA RECUPERAR SENHA
$obRouter->post('/recovery', [
    function($request) {
        return new Response(200, Pages\Recovery::setRecovery($request));
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