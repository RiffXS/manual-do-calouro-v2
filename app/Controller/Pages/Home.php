<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Models\Entity\Organization;

class Home extends Page{

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getHome(){

        $obOrganization = new Organization;

        // VIEW DA HOME
        $content =  View::render('pages/home',[
            'name' => $obOrganization->name
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('HOME > WDEV', $content);
    }
}