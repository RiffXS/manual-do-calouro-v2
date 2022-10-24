<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA MAPA
$obRouter->get('/map', [
    function() {
        return new Response(200, Pages\Map::getMap());
    }
]);
