<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Rod extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página rod
     * @return string
     */
    public static function getRod(): string {
        // VIEW DA HOME
        $content = View::render('pages/rod');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('ROD', $content, 'rod');
    }
}