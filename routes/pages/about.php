<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA SOBRE
$obRouter->get('/about', [
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);
