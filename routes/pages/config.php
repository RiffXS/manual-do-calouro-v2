<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CONTATO
$obRouter->get('/config', [
    function() {
        return new Response(200, Pages\Config::getConfig());
    }
]);