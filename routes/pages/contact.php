<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CONTATO
$obRouter->get('/contact', [
    function($request) {
        return new Response(200, Pages\Contact::getContact($request));
    }
]);

// ROTA CADASTRO DE CONTATO
$obRouter->post('/contact/new', [
    function($request) {
        return new Response(200, Pages\Contact::setNewContact($request));
    }
]);

// ROTA EDIÇÃO DE CONTATO
$obRouter->get('/contact/edit/{id}', [
    function($request, $id) {
        return new Response(220, Pages\Contact::getContactData($request, $id));
    }
]);
