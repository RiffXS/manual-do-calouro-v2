<?php

use App\Http\Response;
use App\Controller\Pages;

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
