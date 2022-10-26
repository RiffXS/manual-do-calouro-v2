<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Faq extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getFaq() {
        // VIEW DA HOME
        $content = View::render('pages/faq');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Faq', $content, 'faq');
    }
}