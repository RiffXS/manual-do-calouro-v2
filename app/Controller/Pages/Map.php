<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Map extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página mapa
     * @return string
     */
    public static function getMap(): string {
        // VIEW DA HOME
        $content = View::render('pages/map');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Mapa', $content, 'map');
    }
}