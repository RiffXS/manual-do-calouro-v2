<?php

namespace App\Http\Middleware;

use \App\Utils\Session;

class UserLogout {

    /**
     * Methodo responsavel por executar o middleware
     * @param \App\Http\Request
     * @param \Closure
     * @return \App\Http\Response
     */
    public function handle($request, $next) { 
        // VERIFICA SE O USUARIO ESTA LOGADO
        if (Session::isLogged()) {
            $request->getRouter()->redirect('/');
        }
        // CONTINUA A EXECUÇÃO
        return $next($request);
    }
}