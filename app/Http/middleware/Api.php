<?php

namespace App\Http\Middleware;

class Api {

    /**
     * Methodo responsavel por executar o middleware
     * @param \App\Http\Request
     * @param \Closure
     * @return \App\Http\Response
     */
    public function handle($request, $next) { 
        // ALTERA O CONTENT TYPE PARA JSON
        $request->getRouter()->setContentType('application/json');

        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
