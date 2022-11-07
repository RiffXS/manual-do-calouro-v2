<?php

namespace App\Controller\Pages;

use App\Models\Calendar as EntityCalendar;
use App\Utils\View;

class Calendar extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página calendario
     * @return string 
     */
    public static function getCalendar() {
        // VIEW DA HOME
        $content = View::render('pages/calendar');

        // VERIFICA SE O COOKIE COM EVENTOS EXISTE
        if (!isset($_COOKIE['mdc-calendario'])) {
            self::setEventsCookie();
        }
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Calendario', $content, 'calendar');
    }
    
    /**
     * Método responsavel por criar o COOKIE de eventos do calendario
     */
    private static function setEventsCookie(): void {
        // NOVA INSTANCIA
        $obCalendar = new EntityCalendar;
        
        // TRANFORMA O ARRAY PARA O FORMATO JSON
        $mdcEvents = json_encode($obCalendar->getEvents());

        // DEFINE O COOKIE DE EVENTOS
        setcookie('mdc-calendario', $mdcEvents);
    }
}