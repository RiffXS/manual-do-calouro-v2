<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Mail\Email;
use App\Utils\Sanitize;
use App\Utils\Session;
use App\Utils\Tools\Alert;
use App\Utils\View;

class Sac extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página fale conosco
     * @param \App\Http\Request $request
     * 
     * @return string 
     * 
     * @author @SimpleR1ick
     */
    public static function getSac(Request $request): string {
        // VIEW DA HOME
        $content = View::render('pages/sac', [
            'status' => Alert::getStatus($request),
            'email'  => Session::getSessionUser()->getEmail()
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Fale Conosco', $content);
    }

    /**
     * Método responsável por enviar o formulário e encaminhar o email
     * @param \App\Http\Request
     * 
     * @return void
     * 
     * @author @SimpleR1ick
     */
    public static function setSac(Request $request): void {
        // POST VARS
        $postVars = Sanitize::sanitizeForm($request->getPostVars());

        $emailTarget = 'manualdocalouro.ifes@gmail.com';
        $phoneNumber = $postVars['telefone'];
        $subjectText = $postVars['assunto'];
        $messageText = $postVars['texto'];

        // NOVA INSTANCIA
        $obEmail = new Email;
        
        // VERIFICA SE O PROCESSO DE ENVIO FOI EXECUTADO
        if ($obEmail->sendEmail($emailTarget, $subjectText, $messageText)) {
            // REDIRECIONA COM MENSAGEM DE SUCESSO
            $request->getRouter()->redirect('/sac?status=email_sent'); 
        } else {
            // REDIRECIONA COM MENSAGEM DE ERRO
            $request->getRouter()->redirect('/sac?status=email_erro'); 
        }
    }
}