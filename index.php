<?php
require __DIR__.'/includes/app.php';

use App\Http\Router;

// INICIA O ROUTER
$obRouter = new Router(URL);

// INCLUI AS ROTAS DO PAINEL ADMINISTRATIVO
include __DIR__.'/routes/admin.php';

// INCLUI AS ROTAS DA API REST
include __DIR__.'/routes/api.php';

// INCLUI AS ROTAS DAS PAGINAS DO PROJETO
include __DIR__.'/routes/pages.php';

// IMPRIME O RESPONSE DA ROTA
$obRouter->run()->sendResponse();