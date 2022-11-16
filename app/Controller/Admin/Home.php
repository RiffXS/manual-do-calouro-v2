<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Home extends Page {

    /**
     * Método responsavel por rendenizar a view de home no painel
     * @return string
     */
    public static function getHome(): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/home/index');

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Home > MDC', $content, 'home');
    }
}