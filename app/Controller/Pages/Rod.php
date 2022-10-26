<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Rod extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getRod() {
        // VIEW DA HOME
        $content = View::render('pages/rod');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Rod', $content, 'rod');
    }
}