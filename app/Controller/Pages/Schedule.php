<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Models\Entity\Schedule as EntitySchedule;

class Schedule extends Page {
    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina horario
     * @param \App\Http\Request
     * @return string 
     */
    public static function getSchedule($request) {
        // VIEW DO HORÁRIO
        $content =  View::render('pages/schedule', [
            ''
        ]);
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        $curso = $queryParams['curso'] ?? '';
        $modulo = $queryParams['modulo'] ?? '';

        // VERIFICA SE AMBOS NÃO ESTÃO VAZIOS
        if (!empty($curso) and !empty($modulo)) {
            // OBTEM OS DADOS PARA TABELA
            Self::getTable($curso, $modulo);
        }

        // RETORNA A VIEW DA PAGINA
        return parent::getHeader('Horários', $content, 'schedule');
    }

    /**
     * Metodo responsavel por retornar a view da tabela do horario
     * 
     */
    public static function getTable($curso, $modulo) {
        $obSchedule = new EntitySchedule;
        $obSchedule->getSchedules($curso, $modulo);

        echo '<pre>'; print_r($obSchedule); echo '</pre>'; exit;
    }  
}