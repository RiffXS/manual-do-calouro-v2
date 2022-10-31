<?php

namespace App\Controller\Pages;

use App\Models\Schedule as EntitySchedule;
use App\Utils\View;

class Schedule extends Page {
    
    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina horario
     * @param \App\Http\Request
     * @return string 
     */
    public static function getSchedule($request): string {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        $curso = $queryParams['curso'] ?? '';
        $modulo = $queryParams['modulo'] ?? '';

        // VERIFICA SE HOUVE EXISTE PARAMETROS PRA CONSULTA DE HORARIOS
        if (!empty($curso) and !empty($modulo)) {
            // OBTEM OS DADOS PARA TABELA
            $tableSchedule = self::getTable($curso, $modulo);

            $content = View::render('pages/schedule', [
                'horarios' => $tableSchedule,
                'hidden'   => '',
                'curso'    => self::getCurso($curso),
                'modulo'   => $modulo
            ]);
        // RENDENIZA A O HORARIO SEM TABELA    
        } else {
            $content = View::render('pages/schedule', [
                'horarios' => '',
                'hidden'   => 'd-none',
                'curso'    => '',
                'modulo'   => ''
            ]);
        }
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Horários', $content, 'schedule');
    }

    /**
     * Metodo responsavel por retornar a view da tabela do horario
     * @param integer $curso
     * @param integer $modulo
     * @return string
     */
    public static function getTable($curso, $modulo): string {
        $obSchedule = EntitySchedule::getSchedule($curso, $modulo);
        $obTime     = EntitySchedule::getScheduleTime();

        $count = 0;
        $content = '';
        
        for ($i = 0; $i < count($obTime); $i++) {
            $content .= View::render('pages/schedule/row', [
                'hora_inicio' => $obTime[$i]['hora_aula_inicio'],
                'hora_fim'    => $obTime[$i]['hora_aula_fim'],
                'aulas'       => self::getRow($obSchedule, $count)
            ]);
        }
        // RETORNA O CONTEÚDO DA PÁGINA
        return $content;
    }
    
    /**
     * Metodo responsavel por rendenizar a linha de items do horario
     * @param array $obSchedule
     * @param integer $count
     * @return string
     */
    public static function getRow($obSchedule, &$count): string {        
        $content = '';
        
        // Loop para cada aula
        for ($i = 0; $i < 6; $i++) { 
            // VIEW DO HORÁRIO
            $content .= self::getItem($obSchedule[$count]);
            $count++;
        }
        // RETORNA A VIEW DA LINHA
        return $content;
    }

    /**
     * Metodo responsavel por criar cada item de aula da tabela
     * @param array $class
     * @return string
     */ 
    public static function getItem($class): string {
        // RETORNA A VIEW DA COLUNA
        return View::render('pages/schedule/item', [
            'sala' => $class['sala'],
            'materia' => $class['materia'],
            'professor' => $class['professor']
        ]);
    }

    /**
     * Metodo responsavel por retornar o curso
     * @param  integer $curso
     * @return string
     */
    private static function getCurso($curso): string {
        // CURSO
        $curso = EntitySchedule::getCursoById($curso);
        
        // RETORNA O NOME DO CURSO
        return $curso['dsc_curso'];
    }
}