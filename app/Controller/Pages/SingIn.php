<?php

namespace App\Controller\Pages;

use App\Utils\Session;
use \App\Utils\View;
use App\Models\Entity\User as EntityUser;

class SingIn extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina login
     * @return string 
     */
    public static function getSingIn($errorMessage = null) {
        // STATUS
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        // CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('pages/singin', [
            'status' => $status
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Login', $content);
    }

    /**
     * Metodo responsavel por realizar login no site
     * @param \App\Http\Request
     */
    public static function setSingIn($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $email = $postVars['email'];
        $senha = $postVars['senha'];

        // MENSAGEM DE ERRO
        $msg = 'E-mail ou senha inválidos!';

        // VALIDA O EMAIL
        $obUser = EntityUser::getUserByEmail($email);

        // VALIDA A INSTANCIA, VERIFICANDO SE HOUVE RESULTADO
        if (!$obUser instanceof EntityUser) {
            return self::getSingIn($msg);
        }
        // VERIFICA A SENHA DO USUARIO CONINCIDE COM A DO BANCO
        if (!password_verify($senha, $obUser->senha)) {
            return self::getSingIn($msg);
        }
        // CRIA A SESSÃO DE LOGIN
        Session::Login($obUser);

        // REDIRECIONA O USUARIO PARA O HOME
        $request->getRouter()->redirect('/');
    }

    /**
     * Mehtodo responsavel por deslogar o usuario
     * @param \App\Http\Request
     */
    public static function setLogout($request) {
        // DESTROI A SESSÃO DE LOGIN
        Session::logout();

        // REDIRECIONA O USUARIO PARA TELA DE LOGIN
        $request->getRouter()->redirect('/');
    }
}