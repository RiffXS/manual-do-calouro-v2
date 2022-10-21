<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Sac extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getSac() {
        // VIEW DA HOME
        $content =  View::render('pages/sac');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Fale Conosco', $content);
    }
}