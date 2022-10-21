<?php

use App\Http\Response;
use App\Controller\Admin;

// ROTA LOGIN
$obRouter->get('/admin/login', [
    'middlewares' => [
        'admin-logout'
    ],
    function($request) {
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

// ROTA LOGIN POST
$obRouter->post('/admin/login', [
    'middlewares' => [
        'admin-logout'
    ],
    function($request) {
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

// ROTA LOGOUT
$obRouter->get('/admin/logout', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Login::setLogout($request));
    }
]);

?>