<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Models\Usuario as EntityUser;
use Closure;
use Exception;

class BasicAuth {

    /**
     * Método responsável por retornar uma istância de usuário autenticado
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
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->getSenha()) ? $obUser : false;
    }

    /**
     * Método responsável por validar o acesso via basic auth
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
     * Método responsável por executar o middleware
     * @param \App\Http\Request
     * @param Closure
     * 
     * @return \App\Http\Response
     */
    public function handle(Request $request, Closure $next): Response { 
        // REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this->basicAuth($request);

        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
