<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Home extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página home
     * @return string 
     */
    public static function getHome() {
        // VIEW DA HOME
        $content = View::render('pages/home');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Home', $content, 'home');
    }
}