<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Aula as EntitySchedule;
use App\Utils\Sanitize;
use App\Utils\View;

class Schedule extends Page {

    /**
     * Array para aulas divididas
     * @var array
     */
    private static $double = [
        'a' => [
            'sala' => '-',
            'materia' => '-',
            'professor' => '-'
        ],
        'b' => [
            'sala' => '-',
            'materia' => '-',
            'professor' => '-'
        ]
    ];
    
    /**
     * Método responsável por retornar o contéudo (view) da página de horario
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getSchedule(Request $request): string {
        // QUERY PARAMS
        $queryParams = Sanitize::sanitizeForm($request->getQueryParams());

        // ATRIBUINDO AS VARIAVEIS
        $curso = $queryParams['curso'] ?? '';
        $modulo = $queryParams['modulo'] ?? '';

        // VERIFICA SE EXISTEM PARAMETROS NA URL
        if (!empty($curso) and !empty($modulo)) {
            // RENDENIZA A PAGINA COM TABELA
            $content = View::render('pages/schedule', [
                'horarios' => self::getTable($curso, $modulo),
                'curso'    => self::getCurso($curso),
                'modulo'   => $modulo,
                'hidden'   => ''
            ]);
        } else {
            // RENDENIZA O PAGINA SEM TABELA 
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
     * Método responsável por retornar o nome do curso
     * @param  integer $curso
     * 
     * @return string
     */
    private static function getCurso(int $curso): string {
        // RETORNA O NOME DO CURSO
        $curso = EntitySchedule::getCursoById($curso);
        return $curso['dsc_curso'];
    }

    /**
     * Método responsável por retornar a view da tabela do horário
     * @param integer $curso 
     * @param integer $modulo
     * 
     * @return string
     */
    public static function getTable(int $curso, int $modulo): string {
        // DECLARAÇÃO DE VARIAVEIS
        $count = 0;
        $content = '';

        // ARRAY COM AULAS DA TURMA CONSULTADA POR CURSO/MODULO
        $aulas = EntitySchedule::getScheduleClass($curso, $modulo);

        // ARRAY COM TODOS OS HORARIOS ESTATICOS DO BANCO
        $horas = EntitySchedule::getScheduleTimes();

        for ($i = 0; $i < count($horas); $i++) {
            // RENDENIZA AS LINHAS DA TABELA
            $content .= View::render('pages/components/schedule/row', [
                'hora_inicio' => $horas[$i]['hora_aula_inicio'],
                'hora_fim'    => $horas[$i]['hora_aula_fim'],
                'aulas'       => self::getRow($aulas, $count)
            ]);
        }
        // RETORNA O CONTEÚDO DA PÁGINA
        return $content;
    }

    /**
     * Método responsavel por definir o array de aula com turma dividida
     * @param array $aulas 
     * @param integer $count
     * 
     * @return void
     */
    private static function setDoubleItem($aulas, &$count): void {
        if ($aulas[$count]['grupo'] == 'A') {
            self::$double['a'] = [
                'sala' => $aulas[$count]['sala'],
                'materia' => $aulas[$count]['materia'],
                'professor' => $aulas[$count]['professor'],
            ];
            if ($aulas[$count+1]['grupo'] == 'B') {
                self::$double['b'] = [
                    'sala' => $aulas[$count+1]['sala'],
                    'materia' => $aulas[$count+1]['materia'],
                    'professor' => $aulas[$count+1]['professor'],
                ];
                $count++;
            }
        } else {
            self::$double['b'] = [
                'sala' => $aulas[$count]['sala'],
                'materia' => $aulas[$count]['materia'],
                'professor' => $aulas[$count]['professor'],
            ];
        }
    }
    
    /**
     * Método responsável por retornar a view de uma linha
     * @param  array   $aulas
     * @param  integer $count
     * 
     * @return string
     */
    public static function getRow(array $aulas, int &$count): string {        
        // DECLARAÇÃO DE VARIAVEL
        $content = '';

        // RENDENIZA OS SEIS ITEMS DE UMA LINHA
        for ($i = 0; $i < 6; $i++) {
            if ($aulas[$count]['grupo'] != 'C') {

                self::setDoubleItem($aulas, $count);
    
                // VIEW DE TURMA DIVIDIDA A-B
                $content .= self::getDoubleClass(self::$double);
            } 
            else {
                // VIEW DE TURMA COMPLETA
                $content .= self::getClass($aulas[$count]);
            }
            // AUMENTA O CONTADOR
            $count++;
        }
        // RETORNA A VIEW DA LINHA
        return $content;
    }

    /**
     * Método responsavel por retorna a view de um item duplo
     * @param array @double
     * 
     */
    private static function getDoubleClass($double) {
        return View::render('pages/components/schedule/double', [
            'sala-a'      => $double['a']['sala'],
            'materia-a'   => $double['a']['materia'],
            'professor-a' => $double['a']['professor'],
            'sala-b'      => $double['b']['sala'],
            'materia-b'   => $double['b']['materia'],
            'professor-b' => $double['b']['professor']
        ]);
    }

    /**
     * Método responsável retornar a view de um item
     * @param  array $class
     * 
     * @return string
     */ 
    private static function getClass(array $aula): string {
        // RETORNA A VIEW DA COLUNA
        return View::render('pages/components/schedule/item', [
            'sala' => $aula['sala'],
            'materia' => $aula['materia'],
            'professor' => $aula['professor']
        ]);
    }
}