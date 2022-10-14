<?php

namespace App\Http\Middleware;

class Maintenence {

    /**
     * Methodo responsavel por executar o middleware
     * @param \App\Http\Request
     * @param \Closure
     * @return \App\Http\Response
     */
    public function handle($request, $next) { 
        // VERIFICA O ESTADO DE MANUTENÇÃO DA PAGINA  
        if (getenv('MAINTENANCE') == 'true') {
            throw new \Exception('Pagina em manutenção. Tente novamente mais tarde.', 200);
        }
        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
