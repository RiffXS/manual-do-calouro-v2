<?php 

use App\Http\Response;
use App\Controller\Admin;

// ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/admin/comments', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Comment::getComments($request));
    }
]);

// ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$obRouter->post('/admin/comments/del', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Comment::setDeleteComment($request));
    }
]);
