<?php

use App\Http\Response;
use App\Controller\Admin;

// ROTA ADMIN
$obRouter->get('/admin/schedule', [
    'middlewares' => [
        'admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Schedule::getSchedule());
    }
]);
