<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\Sanitize;
use App\Utils\Session;
use App\Utils\Tools\Alert;
use App\Utils\View;

class Config extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página de configurações
     * @return string 
     */
    public static function getConfig($request): string {
        // VIEW DO SOBRE
        $content = View::render('pages/config', [
            'status' => Alert::getStatus($request)
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Configurações', $content);
    }

    /**
     * Método responsavel por processar o formulario de redefinir senha atual
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setNewPassword(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        // BUSCA O USUARIO PELO ID
        $obUser = EntityUser::getUserById(Session::getId());

        // VERIFICA SÉ A SENHA ESTA CORRETA
        if (!password_verify($postVars['senha-atual'], $obUser->getSenha())) {
            $request->getRouter()->redirect('/config?status=invalid_pass');
        }
        // VALIDA SE AS SENHAS ESTÃO IGUAIS
        if (Sanitize::validatePassword($postVars['senha-nova'], $postVars['senha-contra'])) {
            $request->getRouter()->redirect('/config?status=invalid_pass');
        }
        // SET DA NOVA SENHA
        $obUser->setSenha($postVars['senha-nova']);

        $obUser->updateUser();

        // REDIRECIONA O USUARIO COM MENSAGEM
        $request->getRouter()->redirect('/config?status=user_updated');
    }

    /**
     * Summary of setDeleteAcount
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setDeleteAcount(Request $request): void {
        // OBTEM O USUARIO PELO ID DA SESSÃO
        $obUser = EntityUser::getUserById(Session::getId());

        // TENTA EXCLUIR A CONTA
        if ($obUser->deleteUser()) {
            // REALIZA O LOGOUT
            Session::logout();
            $request->getRouter()->redirect('/');
        }
        // REDIRECIONA O USUARIO COM MENSAGEM
        $request->getRouter()->redirect('/config?status=delete_error');
    }
}