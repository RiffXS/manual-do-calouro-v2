<?php

namespace App\Controller\Pages;

use App\Models\Mail\Email;
use App\Models\User as EntityUser;
use App\Models\Hash as EntityHash;
use App\Utils\Tools\Alert;
use App\Utils\View;

class Recovery extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina recuperação da senha
     * @return string 
     */
    public static function getRecovery($request) {
        // VIEW DA HOME
        $content = View::render('pages/recovery', [
            'status' => Alert::getStatus($request)
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Recuperar Senha', $content);
    }

    /**
     * 
     * @param \App\Http\Request
     */
    public static function setRecovery($request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        $email = $postVars['email'];

        // VALIDA O EMAIL
        if (EntityUser::validateUserEmail($email)) {
            $request->getRouter()->redirect('/profile?status=invalid_email');
        }
        // BUSCA O USUARIO PELO EMAIL
        $obUser = EntityUser::getUserByEmail($email);
        
        // VERIFICA SE OBTEVE UM USUARIO COM ESSE EMAIL
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/recovery?status=invalid_email');
        }
        // OBTEM ID DO USUARIO
        $id = $obUser->getUserId();

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