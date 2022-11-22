<?php

use App\Http\Response;
use App\Controller\Admin;

// ROTA ADMIN
$obRouter->get('/admin/contacts', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Contact::getContacts($request));
    }
]);
