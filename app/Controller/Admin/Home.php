<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Home extends Page {

    /**
     * Methodo responsavel por rendenizar a view de home no painel
     * @param \App\Http\Request
     * @return string
     */
    public static function getHome() {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/home/index', []);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Home > WDEV', $content, 'home');
    }
}