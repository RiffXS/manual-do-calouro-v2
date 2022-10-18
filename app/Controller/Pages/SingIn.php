<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class SingIn extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina login
     * @return string 
     */
    public static function getSingIn() {
        // VIEW DA HOME
        $content =  View::render('pages/singin');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Login', $content);
    }

    public static function setSingIn($request) {
        
    }
}