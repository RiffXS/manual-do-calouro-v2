<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Models\Usuario as EntityUser;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Closure;
use Exception;

class JWTAuth {

    /**
     * Metodo responsavel por retornar uma istancia de usuario autenticado
     * @param \App\Http\Request $request
     * 
     * @return EntityUser|bool
     */
    private function getJWTAuthUser(Request $request): mixed {
        // HEADERS
        $headers = $request->getHeaders();

        // TOKEN PURO EM JWT
        $jwt = isset($headers['authorization']) ? str_replace('Bearer', '', $headers['authorization']) : '';
        $key = new Key(getenv('JWT_KEY'), 'HS256');

        // DECODIFICAÇÃO DO TOKEN
        try {
            $decode = JWT::decode($jwt, $key);

        } catch(Exception $e) {
            throw new Exception("Token invalido", 403);
        }
        $decode = (array)$decode;

        $email = $decode['email'] ?? '';

        // BUSCA USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($email);

        // VERIFICA INSTANCIA
        if ($obUser instanceof EntityUser) {
            return $obUser;
        }
        return false;
    }

    /**
     * Methodo responsavel por validar o acesso via JWT
     * @param \App\Http\Request $request
     * 
     * @return boolean
     */
    private function auth(Request $request): bool {
        // VERIFICA O USUARIO RECEBIDO
        if ($obUser = $this->getJWTAuthUser($request)) {
            $request->user = $obUser;
            return true;
        }
        // EMITE O ERRO DE USUARIO OU SENHA INVALIDA 
        throw new Exception("Acesso negado", 403);
    }

    /**
     * Methodo responsavel por executar o middleware
     * @param \App\Http\Request
     * @param Closure
     * 
     * @return \App\Http\Response
     */
    public function handle(Request $request, Closure $next): Response { 
        // REALIZA A VALIDAÇÃO DO ACESSO VIA JWT
        $this->auth($request);

        // EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }
}
