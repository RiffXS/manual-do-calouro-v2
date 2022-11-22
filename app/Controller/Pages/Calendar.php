<?php

namespace App\Controller\Pages;

use App\Models\Evento as EntityCalendar;
use App\Utils\View;

class Calendar extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página calendario
     * @return string 
     */
    public static function getCalendar(): string {
        // VIEW DA HOME
        $content = View::render('pages/calendar');

        // VERIFICA SE O COOKIE COM EVENTOS EXISTE
        if (!isset($_COOKIE['mdc-calendario'])) {
            // TRANSFORMA O ARRAY PARA JSON
            $events = EntityCalendar::getEvents(null, null, null, 'dsc_evento, dat_evento')->fetchAll(\PDO::FETCH_ASSOC);

            $cookie = json_encode($events, JSON_UNESCAPED_UNICODE);

            // DEFINE O COOKIE DE EVENTOS
            setcookie('mdc-calendario', $cookie); 
        }
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Calendario', $content, 'calendar');
    }
}