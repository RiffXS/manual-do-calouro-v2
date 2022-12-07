<?php

namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;
use Closure;

class Api {

    /**
     * Método responsável por executar o middleware
     * @param \App\Http\Request
     * @param \Closure
     * 
     * @return \App\Http\Response
     */
    public function handle(Request $request, Closure $next): Response { 
        // ALTERA O CONTENT TYPE PARA JSON
        $request->getRouter()->setContentType('application/json');

        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
