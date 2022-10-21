<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Profile extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function setEditProfile() {
        // VIEW DA HOME
        $content =  View::render('pages/profile');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Perfil', $content);
    }
}