<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\Sanitize;
use App\Utils\Session;
use App\Utils\Tools\Alert;
use App\Utils\View;

class SignIn extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página login
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getSignIn(Request $request): string {
        // CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('pages/signin', [
            'status' => Alert::getStatus($request)
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Login', $content);
    }

    /**
     * Método responsável por realizar login no site
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setSignIn(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        $email = $postVars['email'];
        $senha = $postVars['senha'];

        // VALIDA O EMAIL
        if (Sanitize::validateEmail($email)) {
            $request->getRouter()->redirect('/signin?status=invalid_email');
        }
        $obUser = EntityUser::getUserByEmail($email);

        // VALIDA A INSTANCIA, VERIFICANDO SE HOUVE RESULTADO
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/signin?status=invalid_data');
        }
        // VERIFICA A SENHA DO USUARIO CONINCIDE COM A DO BANCO
        if (!password_verify($senha, $obUser->getSenha())) {
            $request->getRouter()->redirect('/signin?status=invalid_data');
        }
        // VERIFICA SE O USUÁRIO ESTÁ ATIVO
        if (!$obUser->getAtivo() != 0) {
            $request->getRouter()->redirect('/signin?status=inactive_user');
        }
        // CRIA A SESSÃO DE LOGIN
        Session::Login($obUser);

        // REDIRECIONA O USUARIO PARA O HOME
        $request->getRouter()->redirect('/');
    }

    /**
     * Método responsável por deslogar o usuário
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setLogout(Request $request): void {
        // DESTROI A SESSÃO DE LOGIN
        Session::logout();

        // REDIRECIONA O USUARIO PARA TELA DE LOGIN
        $request->getRouter()->redirect('/');
    }
}