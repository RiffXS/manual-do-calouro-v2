<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA LOGIN
$obRouter->get('/signin', [
    'middlewares' => [
        'require-user-logout'
    ],
    function($request) {
        return new Response(200, Pages\Signin::getSignin($request));
    }
]);

// ROTA LOGIN
$obRouter->post('/signin', [
    function($request) {
        return new Response(200, Pages\SignIn::setSignIn($request));
    }
]);

// ROTA LOGOUT
$obRouter->get('/signout', [
    'middlewares' => [
        'require-user-login'
    ],
    function($request) {
        return new Response(200, Pages\SignIn::setLogout($request));
    }
]);

?>