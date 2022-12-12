<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Constant\Curso as EntityCourse;
use App\Models\Constant\HorarioAula as EntityTime;
use App\Models\Constant\SalaAula as EntityClass;
use App\Models\Aula as EntitySchedule;
use App\Utils\Sanitize;
use App\Utils\Tools\Alert;
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
     * Método responsável por retornar o contéudo (view) da página de horário
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
            // RENDERIZA A PAGINA COM TABELA
            $content = View::render('pages/schedule', [
                'status'   => Alert::getStatus($request),
                'table'    => '',
                'horarios' => self::getTable($curso, $modulo),
                'curso'    => EntityCourse::getCursoById($curso)['sigla_curso'],
                'modulo'   => $modulo,
                'hidden'   => ''
            ]);
        } else {
            // RENDERIZA O PAGINA SEM TABELA 
            $content = self::getNoTable($request);
        }
        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Horários', $content, 'schedule');
    }

    /**
     * Método responsavel por renderizar a tabela de consulta de sala
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getAvailability(Request $request): string {
        // POST VARS
        $postVars = $request->getPostVars();
        $content = '';
  
        // VERIFICA SE ALGUMA SALA FOI RECEBIDA
        if (empty($postVars['sala'])) {
            $request->getRouter()->redirect('/schedule?status=non_existent');
        }
        $result = EntityClass::getOccupiedClass($postVars['sala']);

        // VERIFICA SE A CONSULTA OBTEVE RESULTADO
        if (count($result) == 0) {
            $request->getRouter()->redirect('/schedule?status=non_existent');
        }
        
        foreach ($result as $class) {
            $content .= View::render('pages/components/schedule/class', [
                'dia' => $class['dia'],
                'ini' => $class['hora_inicio'],
                'fim' => $class['hora_fim']
            ]);
        }
        // RENDERIZA A TABELA DE HORARIOS DA SALA
        $table = View::render('pages/components/schedule/search', [
            'items' => $content
        ]);
        $content = self::getNoTable($request, $table);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Horários', $content, 'schedule');
    }

    /**
     * Método responsavel por rendenizar a pagina sem a tabela principal
     * @param \App\Http\Request $request
     * @param string $table
     * 
     * @return string
     */
    private static function getNoTable(Request $request, string $table = ''): string {
        return View::render('pages/schedule', [
            'status'   => Alert::getStatus($request),
            'table'    => $table,
            'horarios' => '',
            'curso'    => '',
            'modulo'   => '',
            'hidden'   => 'd-none'
        ]);
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
        $horas = EntityTime::getTimes();

        for ($i = 0; $i < count($horas); $i++) {
            // RENDERIZA AS LINHAS DA TABELA
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
     * Método responsável por definir o array de aula com turma dividida
     * @param array $aulas 
     * @param integer $count
     * 
     * @return array
     */
    private static function setDoubleItem($aulas, &$count): array {
        $double = self::$double;

        if ($aulas[$count]['grupo'] == 'A') {
            $double['a'] = [
                'sala' => $aulas[$count]['sala'],
                'materia' => $aulas[$count]['materia'],
                'professor' => $aulas[$count]['professor'],
            ];

            if ($aulas[$count+1]['grupo'] == 'B') {
                $double['b'] = [
                    'sala' => $aulas[$count+1]['sala'],
                    'materia' => $aulas[$count+1]['materia'],
                    'professor' => $aulas[$count+1]['professor'],
                ];

                $count++;
            }

        } else {
            $double['b'] = [
                'sala' => $aulas[$count]['sala'],
                'materia' => $aulas[$count]['materia'],
                'professor' => $aulas[$count]['professor'],
            ];
        }

        return $double;
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

                $double = self::setDoubleItem($aulas, $count);
    
                // VIEW DE TURMA DIVIDIDA A-B
                $content .= self::getDoubleClass($double);
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
     * Método responsável por retornar a view de um item duplo
     * @param array @double
     * 
     * @return string
     */
    private static function getDoubleClass(array $double): string {
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