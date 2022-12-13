<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Aula as EntitySchedule;
use App\Utils\Database;
use App\Utils\Tools\Alert;
use App\Utils\Pagination;
use App\Utils\View;

class Schedule extends Page {

    /**
     * Método responsavel por obter a renderização dos items de usuarios para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    private static function getScheduleItems(Request $request, &$obPagination): string {
        // USUARIOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntitySchedule::getSchedules(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 10);

        // RESULTADOS DA PAGINA
        $results = EntitySchedule::getDscSchedules('id_aula ASC', (int)$obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obShedule = $results->fetch(\PDO::FETCH_ASSOC)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/schedules/item',[
                'click' => "onclick=deleteItem({$obShedule['id_aula']})",
                'id'         => $obShedule['id_aula'],
                'semana'     => $obShedule['dsc_dia_semana'],
                'horario'    => $obShedule['hora_aula_inicio'],
                'sala'       => $obShedule['dsc_sala_aula'],
                'disciplina' => $obShedule['dsc_disciplina'],
                'professor'  => $obShedule['nom_usuario']
            ]);
        }

        // RETORNA OS DEPOIMENTOS
        return $itens;
    }

       /**
     * Método responsavel por renderizar a view de listagem de usuarios
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getSchedules(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/schedules/index', [
            'itens'      => self::getScheduleItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Horários > MDC', $content, 'schedules');
    }
    
    /**
     * Método responsavel por renderizar o formulario de cadastro de aula
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getNewSchedule(Request $request): string {
        $obDatabase = new Database;

        $content = View::render('admin/modules/schedules/form', [
           'tittle'     => 'Cadastrar Aula',
           'status'     => Alert::getStatus($request),
           'hidden'     => '',
           'semana'     => self::getWeekDays($obDatabase),
           'horario'    => self::getSchedule($obDatabase),
           'sala'       => self::getRooms($obDatabase),
           'disciplina' => self::getSubjects($obDatabase),
           'professor'  => self::getTeachers($obDatabase)
        ]);

        return parent::getPanel('Cadastrar Aula > MDC', $content, 'horario');
    }

    /**
     * Método responsavel por processar o formulario de cadastro
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setNewSchedule(Request $request): void {
        $postvars = $request->getPostVars();

        $obSchedule = new EntitySchedule;



        echo '<pre>'; print_r($postvars); echo '</pre>'; exit;
    }

    /**
     * Método responsavel por renderizar o formulario de edição de aula
     * @param \App\Http\Request $request
     * @param integer $id
     * 
     * @return string
     */
    public static function getEditSchedule(Request $request, int $id): string {
        $schedule = EntitySchedule::getSchedules("id_aula = $id")->fetch(\PDO::FETCH_ASSOC);

        $obDatabase = new Database;

        $content = View::render('admin/modules/schedules/form', [
            'tittle'     => 'Editar Aula',
            'status'     => Alert::getStatus($request),
            'hidden'     => parent::setHiddens($schedule),
            'semana'     => self::getWeekDays($obDatabase),
            'horario'    => self::getSchedule($obDatabase),
            'sala'       => self::getRooms($obDatabase),
            'disciplina' => self::getSubjects($obDatabase),
            'professor'  => self::getTeachers($obDatabase)
         ]);
         // RETORNA O CONTEUDO
         return parent::getPanel('Editar Aula > MDC', $content, 'horario');
    }

    /**
     * Método responsavel por processaro formulario de edicão de aula
     * @param \App\Http\Request $request
     * @param integer $id
     * 
     * @return void
     */
    public static function setEditSchedule(Request $request, int $id): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // CONSULTA SEUS DADOS PELO ID
        $obSchedule = EntitySchedule::getScheduleById($id);

        // VALIDA A INSTANCIA DO OBJETO
        if (!$obSchedule instanceof EntitySchedule) {
            $request->getRouter()->redirect('/admin/schedules');
        }
        // DECLARAÇÃO DE VARIAVEIS
        $semana     = $postVars['dia_semana'] ?? '';
        $horario    = $postVars['horario'] ?? '';
        $sala       = $postVars['sala_aula'] ?? '';
        $disciplina = $postVars['disciplina'] ?? '';
        $professor  = $postVars['professor'] ?? '';

        // SET DE ATRIBUTOS
        $obSchedule->setFk_dia_semana($semana);
        $obSchedule->setFk_horario_aula($horario);
        $obSchedule->setFk_sala_aula($sala);
        $obSchedule->setFk_disciplina($disciplina);
        $obSchedule->setFk_professor($professor);

        $obSchedule->updateSchedule();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/schedules?status=schedule_updated');
    }

    /**
     * Método responsavel por excluir uma aula a partir do modal
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setDeleteSchedule(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();
       
        // OBTENDO O USUÁRIO DO BANCO DE DADOS
        $obUser = EntitySchedule::getScheduleById((int)$postVars['id']);

        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntitySchedule) {
            $request->getRouter()->redirect('/admin/schedules');
        }
        // EXCLUIR DEPOIMENTO
        $obUser->deleteSchedule();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/schedules?status=schedule_deleted');
    }

    /**
     * Método responsável por renderizar as opções de dia da semana
     * @param \App\Utils\Database $obDatabase
     * 
     * @return string
     */
    private static function getWeekDays(Database $obDatabase): string {
        // DECLARAÇÃO DE VARIAVEIS
        $content = '';
        $diaSemana = $obDatabase->find('dia_semana');

        // RENDERIZA AS OPÇÕES DA SEMANA
        for ($i = 0; $i < count($diaSemana); $i++) {
            $content .= self::getOption($diaSemana[$i], [
                'id_dia_semana',
                'dsc_dia_semana'
            ]);
        }
        // RETORNA O CONTEUDO
        return $content;
    }

    /**
     * Método 
     * @param \App\Utils\Database $obDatabase
     * 
     * @return string
     */
    private static function getSchedule(Database $obDatabase): string {
        // DECLARAÇÃO DE VARIAVEIS
        $content = '';
        $horarioAula = $obDatabase->find('horario_aula');

        // RENDERIZA AS OPÇÕES DE HORARIO
        for ($i = 0; $i < count($horarioAula); $i++) {
            $horario = array(
                'id_horario' => $horarioAula[$i]['id_horario_aula'],
                'inicio_fim' => $horarioAula[$i]['hora_aula_inicio'] . ' - ' . $horarioAula[$i]['hora_aula_fim']
            );

            $content .= self::getOption($horario, [
                'id_horario',
                'inicio_fim'
            ]);
        }
        // RETORNA O CONTEUDO
        return $content;
    }

    /**
     * Método responsavel por renderizar as opções de sala
     * @param \App\Utils\Database $obDatabase
     * 
     * @return string
     */
    private static function getRooms(Database $obDatabase): string {
        // DECLARAÇÃO DE VARIAVEIS
        $content = '';
        $salaAula = $obDatabase->find('sala_aula');

        // RENDERIZA AS OPÇÕES DA SEMANA
        for ($i = 0; $i < count($salaAula); $i++) {
            $content .= self::getOption($salaAula[$i], [
                'id_sala_aula',
                'dsc_sala_aula'
            ]);
        }
        // RETORNA O CONTEUDO
        return $content;
    }

    /**
     * Método responsavel por renderizar as opções de disciplina
     * @param \App\Utils\Database $obDatabase
     * 
     * @return string
     */
    private static function getSubjects(Database $obDatabase): string {
        // DECLARAÇÃO DE VARIAVEIS
        $content = '';
        $disciplina = $obDatabase->find('disciplina');

        // RENDERIZA AS OPÇÕES DAS DISCIPLINA
        for ($i = 0; $i < count($disciplina); $i++) {
            $content .= self::getOption($disciplina[$i], [
                'id_disciplina',
                'dsc_disciplina'
            ]);
        }
        // RETORNA O CONTEUDO
        return $content;
    }

    /**
     * Método responsavel por renderizar as opções de professores
     * @param \App\Utils\Database $obDatabase
     * 
     * @return string
     */
    private static function getTeachers(Database $obDatabase): string {
        $content = '';

        $sql = 'professor p JOIN servidor s ON (p.fk_servidor_fk_usuario_id_usuario = s.fk_usuario_id_usuario) JOIN usuario u ON (s.fk_usuario_id_usuario = u.id_usuario)';

        $professor = $obDatabase->find($sql, 'id_usuario, nom_usuario');

        // RENDERIZA AS OPÇÕES DAS DISCIPLINA
        for ($i = 0; $i < count($professor); $i++) {
            $content .= self::getOption($professor[$i], [
                'id_usuario',
                'nom_usuario'
            ]);
        }
        return $content;
    }

    /**
     * Método responsavel por criar uma view de option
     * @param array $values
     * @param array $keys
     * 
     * @return string
     */
    private static function getOption(array $values, array $keys): string {
        return View::render('/admin/modules/schedules/option', [
            'id'    => $values[$keys[0]],
            'valor' => $values[$keys[1]]
        ]);
    }
}