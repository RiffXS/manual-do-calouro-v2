<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CADASTRO
$obRouter->get('/signup', [
    function($request) {
        return new Response(200, Pages\SignUp::getSignUp($request));
    }
]);

// ROTA CADASTRO
$obRouter->post('/signup', [
    function($request) {
        return new Response(200, Pages\SignUp::setSignUp($request));
    }
]);
