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

        $obOrganization = new Organization;

        // VIEW DA HOME
        $content =  View::render('pages/about',[
            'name'        => $obOrganization->name,
            'description' => $obOrganization->description,
            'site'        => $obOrganization->site
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('SOBRE > WDEV', $content);
    }
}