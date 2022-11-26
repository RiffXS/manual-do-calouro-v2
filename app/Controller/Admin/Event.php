<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Evento as EntityCalendar;
use App\Utils\Tools\Alert;
use App\Utils\Pagination;
use App\Utils\View;

class Event extends Page {

    /**
     * Método responsavel por obter a rendenização dos items de usuarios para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    private static function getEventsItems(Request $request, &$obPagination): string {
        // USUARIOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityCalendar::getEvents(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        // RESULTADOS DA PAGINA
        $results = EntityCalendar::getDscEvents('id_evento DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obCalendar = $results->fetch(\PDO::FETCH_ASSOC)) {  
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/events/item',[
                'id'     => $obCalendar['id_evento'],
                'campus' => $obCalendar['dsc_campus'],
                'data'   => $obCalendar['dat_evento'],
                'dsc'    => $obCalendar['dsc_evento'],
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
    public static function getEvents(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/events/index', [
            'itens'      => self::getEventsItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Eventos > MDC', $content, 'events');
    }
}