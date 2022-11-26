<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Aula as EntitySchedule;
use App\Utils\Tools\Alert;
use App\Utils\Pagination;
use App\Utils\View;

class Schedule extends Page {

    /**
     * Método responsavel por obter a rendenização dos items de usuarios para página
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
        $results = EntitySchedule::getDscSchedules('id_aula ASC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obShedule = $results->fetch(\PDO::FETCH_ASSOC)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/schedules/item',[
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
     * Método responsavel por rendenizar a view de listagem de usuarios
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
        return parent::getPanel('Horarios > MDC', $content, 'schedules');
    }
    
    /**
     * Método responsavel por rendenizar o formulario de cadastro de aula
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getNewSchedule(Request $request): string {
        $content = View::render('admin/modules/schedules/form', [
           'tittle'  => 'Cadastrar aula',
           'status' => Alert::getStatus($request)
        ]);

        return parent::getPanel('Cadastrar aula > MDC', $content, 'horario');
    }
}