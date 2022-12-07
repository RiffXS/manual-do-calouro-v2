<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Usuario as EntityUser;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;

class SignUp extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página de cadastro
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getSignUp($request): string {
        // RENDERIZA O CONTEUDO DA PAGINA DE CADASTRO
        $content = View::render('pages/signup', [
            'status' => Alert::getStatus($request)
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Cadastro', $content);
    }

    /**
     * Método responsável por processar o formulário de cadastro
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setSignUp(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        // ATRIBUINDO AS VARIAVEIS
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
        // CONSULTA O USUARIO UTILIZANDO O EMAIL
        $obUser = EntityUser::getUserByEmail($email);

        // VALIDA A INSTANCIA, VERIFICANDO SE O EMAIL JÁ ESTA SENDO UTILIZADO
        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/signup?status=duplicated_email');
        }
        // NOVA INSTÂNCIA DE USUARIO
        $obUser = new EntityUser;

        // ATRIBUTOS DO OBJETO
        $obUser->setNom_usuario($nome);
        $obUser->setEmail($email);
        $obUser->setSenha($password);
      
        // REALIZA O INSERT NO BANCO DE DADOS
        $obUser->insertUser();

        // REDIRECIONA PARA PAGINA DE LOGIN
        $request->getRouter()->redirect('/signin?status=user_registered');
    }
}