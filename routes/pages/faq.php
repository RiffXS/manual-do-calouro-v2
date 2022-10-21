<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA FAQ
$obRouter->get('/faq', [
    'middlewares' => [
        'user-login'
    ],
    function() {
        return new Response(200, Pages\Faq::getFaq());
    }
]);

?>