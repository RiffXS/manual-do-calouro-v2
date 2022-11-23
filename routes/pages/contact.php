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
$obRouter->post('/contact/edit', [
    function($request) {
        return new Response(200, Pages\Contact::setEditContact($request));
    }
]);

// ROTA EXCLUSÃO DE CONTATO
$obRouter->post('/contact/del', [
    function($request) {
        return new Response(200, Pages\Contact::setDeleteContact($request));
    }
]);

