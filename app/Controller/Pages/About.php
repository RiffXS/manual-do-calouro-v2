<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Models\Entity\Organization;

class About extends Page{

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina sobre
     * @return string 
     */
    public static function getAbout(){
        // VIEW DO SOBRE
        $content =  View::render('pages/about');

        // RETORNA A VIEW DA PAGINA
        return parent::getPanel('Sobre', $content, 'about');
    }
}