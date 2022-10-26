<?php

namespace App\Controller\Pages;

use App\Models\Email;
use App\Utils\Session;
use App\Utils\View;
use App\Utils\Tools\Alert;

class Sac extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * @return string 
     */
    public static function getSac($request) {
        // VIEW DA HOME
        $content = View::render('pages/sac', [
            'status' => Alert::getStatus($request),
            'email'  => Session::getSessionUser()->getEmail()
        ]);
        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Fale Conosco', $content);
    }

    /**
     * @param \App\Http\Request
     */
    public static function setSac($request) {
        // POST VARS
        $postVars = $request->getPostVars();

        $emailTarget = $postVars['email'] ?? 'Não informado';
        $phoneNumber = $postVars['telefone'];
        $subjectText = $postVars['assunto'];
        $messageText = $postVars['texto'];

        // NOVA INSTANCIA
        $obEmail = new Email;
        
        // VERIFICA SE O PROCESSO DE ENVIO FOI EXECUTADO
        if ($obEmail->sendEmail($emailTarget, $subjectText, $messageText)) {
            // SUCESSO
            $request->getRouter()->redirect('/sac?status=email_sent'); 
        } 
        $request->getRouter()->redirect('/sac?status=email_erro'); 

    }
}