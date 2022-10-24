<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA ROD
$obRouter->get('/rod', [
    function() {
        return new Response(200, Pages\Rod::getRod());
    }
]);
