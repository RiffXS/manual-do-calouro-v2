<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Faq extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página fale dúvidas frequentes
     * @return string
     */
    public static function getFaq(): string {
        // VIEW DA HOME
        $content = View::render('pages/faq');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('FAQ', $content, 'faq');
    }
}