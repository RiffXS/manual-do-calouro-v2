<?php

namespace App\Controller\Pages;

use App\Utils\View;

class About extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página sobre
     * @return string 
     */
    public static function getAbout(): string {
        // VIEW DO SOBRE
        $content = View::render('pages/about');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Sobre', $content, 'about');
    }
}