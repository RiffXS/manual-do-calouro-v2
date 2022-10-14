<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout {

    /**
     * Methodo responsavel por executar o middleware
     * @param \App\Http\Request
     * @param \Closure
     * @return \App\Http\Response
     */
    public function handle($request, $next) { 
        // VERIFICA SE O USUARIO ESTA LOGADO
        if (SessionAdminLogin::isLogged()) {
            $request->getRouter()->redirect('/admin');
        }
        // CONTINUA A EXECUÇÃO
        return $next($request);
    }
}