<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Mail\Email;
use App\Models\User as EntityUser;
use App\Models\Hash as EntityHash;
use App\Utils\Tools\Alert;
use App\Utils\Sanitize;
use App\Utils\View;

class Recovery extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página recuperação da senha
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getRecovery(Request $request): string {
        // VIEW DA HOME
        $content = View::render('pages/recovery', [
            'status' => Alert::getStatus($request)
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Recuperar Senha', $content);
    }

    /**
     * Método responsável por criar e enviar o link para recuperação de senha
     * @param \App\Http\Request $request
     */
    public static function setRecovery(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        $email = $postVars['email'];

        // VALIDA O EMAIL
        if (Sanitize::validateEmail($email)) {
            $request->getRouter()->redirect('/profile?status=invalid_email');
        }
        // BUSCA O USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($email);
        
        // VERIFICA SE OBTEVE UM USUARIO COM ESSE EMAIL
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/recovery?status=invalid_email');
        }
        // OBTEM ID DO USUARIO
        $id = $obUser->getId_usuario();

        // NOVA NSTANCIA
        $obHash = new EntityHash;
        $obHash->setFkId($id);
        $obHash->setHash();

        // VERIFICA SE A CHAVE JÁ EXISTE NO BANCO
        if (EntityHash::findHash($id) instanceof EntityHash) {
            $obHash->updateHash(); // ATUALIZA A CHAVE
        } else {
            $obHash->insertHash(); // INSERE A CHAVE
        }
        // NOVA INSTANCIA
        $obEmail = new Email;

        // LINK HTML
        $link = "<a href='http://localhost/mvc-mdc/redefine?chave={$obHash->getHash()}'>Clique aqui</a>";

        // ASSUNTO E MENSAGEM
        $subject = 'Recuperar senha';
        $message = 'Para recuperar sua senha, acesse este link: '.$link;

        // VERIFICA SE O PROCESSO DE ENVIO FOI EXECUTADO
        if ($obEmail->sendEmail($email, $subject, $message)) {
            $request->getRouter()->redirect('/recovery?status=recovery_send'); 
        } 
        else {
            $obHash->deleteHash();
            $request->getRouter()->redirect('/recovery?status=recovery_erro'); 
        }      
    }
}