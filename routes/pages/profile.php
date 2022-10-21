<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA PERFIL
$obRouter->get('/profile', [
    function() {
        return new Response(200, Pages\Profile::setEditProfile());
    }
]);

// ROTA PERFIL
$obRouter->post('/profile', [
    function() {
        return new Response(200, Pages\Profile::setEditProfile());
    }
]);