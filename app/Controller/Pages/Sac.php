<?php

namespace App\Controller\Pages;

use App\Utils\Session;
use \App\Utils\View;
use PHPMailer\PHPMailer\PHPMailer;

class Sac extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getSac() {
        // VIEW DA HOME
        $content =  View::render('pages/sac', [
            'email' => Session::getSessionUser()->getEmail()
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Fale Conosco', $content);
    }

    /**
     * @param \App\Http\Request
     * 
     */
    public static function setSac($request) {
        $postVars = $request->getPostVars();

        $emailTarget = $postVars['email'] ?? 'Não informado';
        $phoneNumber = $postVars['telefone'];
        $subjectText = $postVars['assunto'];
        $messageText = $postVars['texto'];

        
    }
}