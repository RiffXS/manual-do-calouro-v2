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

// ROTA DE EDICAÇÃO DE CONTATO
$obRouter->get('/admin/contacts/edit/{id}', [
    'middlewares' => [
        'admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Contact::getEditContact($request, $id));
    }
]);
