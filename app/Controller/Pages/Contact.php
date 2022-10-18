<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Contact extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina contatos
     * @return string 
     */
    public static function getContact() {
        // VIEW DA HOME
        $content =  View::render('pages/contact');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Contatos', $content, 'contact');
    }
}