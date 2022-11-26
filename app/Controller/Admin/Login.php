<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\View;
use App\Utils\Tools\Alert;
use App\Utils\Session;

class Login extends Page {

    /**
     * Methodo retornar a rendenização da pagina de login
     * @param \App\Http\Request $request
     * @param  string $errorMessage
     * 
     * @return string
     */
    public static function getLogin(Request $request): string {
        // CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('admin/login', [
            'status' => Alert::getStatus($request)
        ]);
        // RETORNA A PAGINA COMPLETA
        return parent::getPage('Login > MDC', $content); 
    }

    /**
     * Methodo responsavel por definir o login do usuario
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setLogin(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        $msg = 'E-mail ou senha inválidos!';

        // BUSCA USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($email);
        
        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/login?status=invalid_data');
        }
        // VERIFICA A SENHA DO USUARIO
        if (!password_verify($senha, $obUser->getSenha())) {
            $request->getRouter()->redirect('/admin/login?status=invalid_data');
        }
        // VERIFICA SE O USUARIO POSSUI O ACESSO NECESSARIO
        if ($obUser->getFk_acesso() != 1) {
            $request->getRouter()->redirect('/admin/login?status=invalid_acess');
        }
        // CRIA A SESSÃO DE LOGIN
        Session::Login($obUser);

        // REDIRECIONA O USUARIO PARA HOME DO ADMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Mehtodo responsavel por deslogar o usuario
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setLogout(Request $request): void {
        // DESTROI A SESSÃO DE LOGIN
        Session::logout();

        // REDIRECIONA O USUARIO PARA TELA DE LOGIN
        $request->getRouter()->redirect('/admin/login');
    }
}