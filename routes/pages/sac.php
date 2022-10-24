<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CONTATO
$obRouter->get('/sac', [
    function() {
        return new Response(200, Pages\Sac::getSac());
    }
]);

// ROTA CONTATO
$obRouter->post('/sac', [
    function($request) {
        return new Response(200, Pages\Sac::setSac($request));
    }
]);

