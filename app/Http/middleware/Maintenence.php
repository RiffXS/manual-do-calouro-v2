<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;

class Maintenence {

    /**
     * Método responsável por executar o middleware
     * @param \App\Http\Request
     * @param Closure
     * 
     * @return \App\Http\Response
     */
    public function handle(Request $request, Closure $next): Response { 
        // VERIFICA O ESTADO DE MANUTENÇÃO DA PAGINA  
        if (getenv('MAINTENANCE') == 'true') {
            throw new \Exception('Pagina em manutenção. Tente novamente mais tarde.', 200);
        }
        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
