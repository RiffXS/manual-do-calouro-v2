<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Utils\Database;
use App\Models\Entity\Schedule as EntitySchedule;

class Schedule extends Page {
    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina horario
     * 
     * @return string 
     */
    public static function getSchedule() {
        // VIEW DO HORÁRIO
        $content =  View::render('pages/schedule', [
            ''
        ]);

        $horarios = EntitySchedule::getSchedules(1, 6);
        echo '<pre>'; print_r($horarios); echo '</pre>'; exit;

        // RETORNA A VIEW DA PAGINA
        return parent::getPanel('Horários', $content, 'schedule');
    }

    /**
     * Metodo responsavel por retornar a view da tabela do horario
     * 
     */
    public static function getTable($request) {
        $queryParams = $request->getQueryParams();


    }

    
}