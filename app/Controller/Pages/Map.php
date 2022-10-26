<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Map extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina mapa
     * 
     * @return string 
     */
    public static function getMap() {
        // VIEW DA HOME
        $content = View::render('pages/map');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Mapa', $content, 'map');
    }
}