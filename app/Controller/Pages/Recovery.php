<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Recovery extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * 
     * @return string 
     */
    public static function getRecovery() {
        // VIEW DA HOME
        $content =  View::render('pages/recovery');

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Recuperar Senha', $content);
    }

    public static function setRecovery($request) {

    }
}