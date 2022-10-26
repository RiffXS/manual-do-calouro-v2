<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CONTATO
$obRouter->get('/sac', [
    function($request) {
        return new Response(200, Pages\Sac::getSac($request));
    }
]);

// ROTA CONTATO
$obRouter->post('/sac', [
    function($request) {
        return new Response(200, Pages\Sac::setSac($request));
    }
]);