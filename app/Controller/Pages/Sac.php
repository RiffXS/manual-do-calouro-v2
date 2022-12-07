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
     */
    public static function getSac(Request $request): string {
        // RENDERIZA O CONTEUDO DA PAGINA DE SAC
        $content = View::render('pages/sac', [
            'status' => Alert::getStatus($request),
            'email'  => Session::getUser()->getEmail()
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Fale Conosco', $content);
    }

    /**
     * Método responsável por enviar o formulário e encaminhar o email
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setSac(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars);

        // ATRIBUINDO AS VARIAVEIS
        $emailTarget = 'manualdocalouro.ifes@gmail.com';
        $phoneNumber = $postVars['telefone'];
        $subjectText = $postVars['assunto'];
        $messageText = $postVars['texto'];

        // NOVA INSTANCIA DE EMAIL
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