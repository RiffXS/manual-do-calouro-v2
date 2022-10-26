<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Home extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getHome() {
        // VIEW DA HOME
        $content = View::render('pages/home');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Home', $content, 'home');
    }
}