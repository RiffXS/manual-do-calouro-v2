<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use App\Models\Entity\User as EntityUser;

class SingUp extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina cadastro
     * @return string 
     */
    public static function getSingUp() {
        // VIEW DA HOME
        $content =  View::render('pages/singup');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Cadastro', $content);
    }

    public static function setSingUp($request) {
         // POST VARS
        $postVars = $request->getPostVars();

        $nome  = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['senha'] ?? '';
        $confirm = $postVars['senhaConfirma'] ?? '';

        // VALIDA O NOME
        if (EntityUser::validateUserName($nome)) {
            $request->getRouter()->redirect('/singup?status=invalidname');
        }
        // VALIDA O EMAIL
        if (EntityUser::validateUserEmail($email)) {
            $request->getRouter()->redirect('/singup?status=invalidemail');
        }
        // VALIDA A SENHA
        if (EntityUser::validateUserPassword($password, $confirm)) {
            $request->getRouter()->redirect('/singup?status=passnotagree');
        }

        // VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($email);

        // VERIFICA SE O EMAIL ESTA DISPONIVEL
        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/singup?status=duplicated');
        }
        // NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser;
        $obUser->nom_usuario = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($password, PASSWORD_DEFAULT);

        $obUser->insertUser();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/singin?status=created');
    }
}