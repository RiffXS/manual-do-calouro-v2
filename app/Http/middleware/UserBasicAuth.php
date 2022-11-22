<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use Closure;
use Exception;

class UserBasicAuth {

    /**
     * Método responsavel por retornar uma istancia de usuario autenticado
     * @return mixed
     */
    private function getBasicAuthUser(): mixed {
        // VERIFICA A EXISTENCIA DOS DADOS DE ACESSO
        if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }
        // BUSCA USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        // VEIRIFICA INSTANCIA
        if (!$obUser instanceof EntityUser) {
            return false;
        }
        // VALIDA A SENHA E RETORNA O USUARIO
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
    }

    /**
     * Método responsavel por validar o acesso via basic auth
     * @param \App\Http\Request $request
     * 
     * @return boolean
     */
    private function basicAuth(Request $request): bool {
        // VERIFICA O USUARIO RECEBIDO
        if ($obUser = $this->getBasicAuthUser()) {
            $request->user = $obUser;
            return true;
        }
        // EMITE O ERRO DE USUARIO OU SENHA INVALIDA 
        throw new Exception("Usuario ou senha invalidos", 403);
    }

    /**
     * Método responsavel por executar o middleware
     * @param \App\Http\Request
     * @param Closure
     * 
     * @return \App\Http\Response
     */
    public function handle(Request $request, Closure $next): Request { 
        // REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this->basicAuth($request);

        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
