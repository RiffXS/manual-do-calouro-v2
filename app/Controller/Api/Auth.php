<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\Sanitize;
use \Firebase\JWT\JWT;
use Exception;

class Auth extends Api {

    /**
     * Método responsavel por gerar um token JWT
     * @param Request $request
     * 
     * @return array
     * 
     * @throws Exception
     */
    public static function generateToken(Request $request): array {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        // VALIDA OS CAMPOS OBRIGATORIOS
        if (!isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new Exception("Os campos 'email' e 'senha' são obrigatorios", 400);
        }
        // BUSCAR USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($postVars['email']);
        if (!$obUser instanceof EntityUser) {
            throw new Exception("O usuario ou senha são invalidos!", 400);
        }
        // VALIDA SENHA DO USUARIO
        if (!password_verify($postVars['senha'], $obUser->getSenha())) {
            throw new Exception("O usuario ou senha são invalidos!", 400);
        }
        // PAYLOAD
        $key = getenv('JWT_KEY');
        $payload = [
            'email' => $obUser->getEmail()
        ];
        
        // RETORNA O TOKEN GERADO
        return [
            'token' => JWT::encode($payload, $key, 'HS256')
        ];
    }
}