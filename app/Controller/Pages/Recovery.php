<?php

namespace App\Controller\Pages;

use App\Utils\Email;
use App\Utils\View;
use App\Utils\Tools\Alert;
use App\Models\Entity\User as EntityUser;
use App\Models\Entity\Hash as EntityHash;

class Recovery extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getRecovery($request) {
        // VIEW DA HOME
        $content =  View::render('pages/recovery', [
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
        // NOVAI NSTANCIA
        $obHash = new EntityHash;

        $obHash->setFkId($id);
        $obHash->generateKey();

        // VERIFICA SE A CHAVE JÁ EXISTE NO BANCO
        if (EntityHash::verifyKey($id)) {
            $obHash->insertKey(); // INSERE A CHAVE
        } else {
            $obHash->updateKey(); // ATUALIZA A CHAVE
        }
        // LINK HTML
        $link = "<a href='http://localhost/mvc-mdc/redefine?chave={$obHash->getKey()}'>Clique aqui</a>";

        // ASSUNTO E MENSAGEM
        $subject = 'Recuperar senha';
        $message = 'Para recuperar sua senha, acesse este link: '.$link;

        // NOVA INSTANCIA
        $obEmail = new Email;

        // VERIFICA SE O PROCESSO DE ENVIO FOI EXECUTADO
        //if ($obEmail->sendEmail($email, $subject, $message)) {
            // SUCESSO
            //$request->getRouter()->redirect('/recovery?status=recovery_send'); 
        //} 
    }
}