<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Config extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página de configurações
     * @return string 
     */
    public static function getConfig(): string {
        // VIEW DO SOBRE
        $content = View::render('pages/config');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Configurações', $content);
    }
}