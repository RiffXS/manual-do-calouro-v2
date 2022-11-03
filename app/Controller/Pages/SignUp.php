<?php

namespace App\Controller\Pages;

use App\Models\User as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;

class SignUp extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina cadastro
     * @param \App\Http\Request $request
     * @return string
     */
    public static function getSignUp($request): string {
        // CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('pages/signup', [
            'status' => Alert::getStatus($request)
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Cadastro', $content);
    }

    /**
     * Método responsavel por processar o formulario de cadastro
     * @param \App\Http\Request $request
     */
    public static function setSignUp($request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        $nome     = $postVars['nome'] ?? '';
        $email    = $postVars['email'] ?? '';
        $password = $postVars['senha'] ?? '';
        $confirm  = $postVars['senhaConfirma'] ?? '';

        // VALIDA O NOME
        if (Sanitize::validateName($nome)) {
            $request->getRouter()->redirect('/signup?status=invalid_name');
        }
        // VALIDA O EMAIL
        if (Sanitize::validateEmail($email)) {
            $request->getRouter()->redirect('/signup?status=invalid_email');
        }
        // VALIDA A SENHA
        if (Sanitize::validatePassword($password, $confirm)) {
            $request->getRouter()->redirect('/signup?status=invalid_pass');
        }

        // VERIFICA O EMAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($email);

        // VERIFICA SE O EMAIL ESTA DISPONÍVEL
        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/signup?status=duplicated_email');
        }
        // NOVA INSTÂNCIA DE USUARIO
        $obUser = new EntityUser;

        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($password);
      
        // CADASTRA O USUÁRIO
        $obUser->insertUser();

        // REDIRECIONA AO LOGIN
        $request->getRouter()->redirect('/signin?status=user_registered');
    }
}