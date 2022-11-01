<?php

namespace App\Controller\Pages;

use App\Models\Schedule as EntitySchedule;
use App\Utils\View;

class Schedule extends Page {
    
    /**
     * Método responsavel por retornar o contéudo (view) da pagina horario
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
            $nome  = self::getCurso($curso);
            $table = self::getTable($curso, $modulo);

            $content = View::render('pages/schedule', [
                'curso'    => $nome,
                'modulo'   => $modulo,
                'horarios' => $table,
                'hidden'   => ''
            ]);
        // RENDENIZA A O HORARIO SEM TABELA    
        } else {
            $content = View::render('pages/schedule', [
                'horarios' => '',
                'curso'    => '',
                'modulo'   => '',
                'hidden'   => 'd-none'
            ]);
        }
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Horários', $content, 'schedule');
    }

    /**
     * Metodo responsavel por retornar o nome do curso
     * @param  integer $curso
     * @return string
     */
    private static function getCurso($curso): string {
        // RETORNA O NOME DO CURSO
        $curso = EntitySchedule::getCursoById($curso);
        return $curso['dsc_curso'];
    }

    /**
     * Metodo responsavel por retornar a view da tabela do horario
     * @param integer $curso
     * @param integer $modulo
     * @return string
     */
    public static function getTable($curso, $modulo): string {
        // DECLARAÇÃO DE VARIAVEIS
        $count = 0;
        $content = '';

        // NOVA INSTANCIA
        $obSchedule = new EntitySchedule($curso, $modulo);

        $horas = $obSchedule->getTimes();
        $aulas = $obSchedule->getClass();

        for ($i = 0; $i < count($horas); $i++) {
            $content .= View::render('pages/schedule/row', [
                'hora_inicio' => $horas[$i]['hora_aula_inicio'],
                'hora_fim'    => $horas[$i]['hora_aula_fim'],
                'aulas'       => self::getRow($aulas, $count)
            ]);
        }
        // RETORNA O CONTEÚDO DA PÁGINA
        return $content;
    }
    
    /**
     * Metodo responsavel por rendenizar a linha de items do horario
     * @param array   $aulas
     * @param integer $count
     * @return string
     */
    public static function getRow($aulas, &$count): string {        
        $content = '';
        
        // Loop para cada aula
        for ($i = 0; $i < 6; $i++) { 
            // VIEW DO HORÁRIO
            $content .= self::getItem($aulas[$count]);
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
    public static function getItem($aula): string {
        // RETORNA A VIEW DA COLUNA
        return View::render('pages/schedule/item', [
            'sala' => $aula['sala'],
            'materia' => $aula['materia'],
            'professor' => $aula['professor']
        ]);
    }
}