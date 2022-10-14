<?php
require __DIR__.'/includes/app.php';

use App\Http\Router;

// INICIA O ROUTER
$obRouter = new Router(URL);

// INCLUI AS ROTAS DAS PAGINAS
include __DIR__.'/routes/pages.php';

// INCLUI AS ROTAS DO PAINEL
include __DIR__.'/routes/admin.php';

// INCLUI AS ROTAS DO API
include __DIR__.'/routes/api.php';

// IMPRIME O RESPONSE DA ROTA
$obRouter->run()
         ->sendResponse();
?>