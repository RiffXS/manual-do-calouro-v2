<?php

namespace App\Controller\Pages;

use App\Utils\Session;
use App\Utils\Tools\Alert;
use \App\Utils\View;
use App\Models\Entity\User as EntityUser;

class SignIn extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina login
     * @return string 
     */
    public static function getSignIn($request) {
        // CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('pages/signin', [
            'status' => Alert::getStatus($request)
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Login', $content);
    }

    /**
     * Metodo responsavel por realizar login no site
     * @param \App\Http\Request
     */
    public static function setSignIn($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $email = $postVars['email'];
        $senha = $postVars['senha'];

        // VALIDA O EMAIL
        $obUser = EntityUser::getUserByEmail($email);

        // VALIDA A INSTANCIA, VERIFICANDO SE HOUVE RESULTADO
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/signin?status=invalid_data');
        }
        // VERIFICA A SENHA DO USUARIO CONINCIDE COM A DO BANCO
        if (!password_verify($senha, $obUser->senha)) {
            $request->getRouter()->redirect('/signin?status=invalid_data');
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