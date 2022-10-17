<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Calendar extends Page {

    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina home
     * @return string 
     */
    public static function getCalendar() {
        // VIEW DA HOME
        $content =  View::render('pages/calendar');

        // RETORNA A VIEW DA PAGINA
        return parent::getPanel('Calendario', $content, 'calendar');
    }
}