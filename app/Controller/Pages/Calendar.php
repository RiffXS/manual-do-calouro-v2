<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Calendar extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página calendario
     * @return string 
     */
    public static function getCalendar() {
        // VIEW DA HOME
        $content = View::render('pages/calendar');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Calendario', $content, 'calendar');
    }
}