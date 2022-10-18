<?php

namespace App\Controller\Pages;

use \App\Utils\View;

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
        
    }
}