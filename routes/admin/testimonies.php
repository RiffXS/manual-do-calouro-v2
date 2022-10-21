<?php 

use App\Http\Response;
use App\Controller\Admin;

// ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/admin/testimonies', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

// ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$obRouter->get('/admin/testimonies/new', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);

// ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$obRouter->post('/admin/testimonies/new', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::setNewTestimony($request));
    }
]);

// ROTA DE EDIÇÃO DE UM DEPOIMENTO
$obRouter->get('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
    }
]);

// ROTA DE EDIÇÃO DE UM DEPOIMENTO (POST)
$obRouter->post('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
    }
]);

// ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$obRouter->get('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
    }
]);

// ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$obRouter->post('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
    }
]);

?>