<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA CONTATO
$obRouter->get('/contact', [
    function() {
        return new Response(200, Pages\Contact::getContact());
    }
]);

?>