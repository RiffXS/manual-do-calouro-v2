<?php

namespace App\Controller\Pages;

use App\Models\Entity\User as EntityUser;
use App\Models\Entity\Hash as EntityHash;
use App\Models\Email;
use App\Utils\View;
use App\Utils\Tools\Alert;

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
        return parent::getHeader('Recuperar Senha', $content);
    }

    /**
     * @param \App\Http\Request
     * 
     */
    public static function setRecovery($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $email = $postVars['email'];

        // VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($email);
        $id = $obUser->getUserId();

        // VALIDA O EMAIL
        if (EntityUser::validateUserEmail($email)) {
            $request->getRouter()->redirect('/profile?status=invalid_email');
        }
        // VERIFICA SE EXISTE UM USUARIO COM ESSE EMAIL
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/recovery?status=invalid_email');
        }
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
            // SUCESSO
            $request->getRouter()->redirect('/recovery?status=recovery_send'); 
        } else {
            $obHash->deleteHash();
            
            $request->getRouter()->redirect('/recovery?status=recovery_erro'); 
        }
    }
}