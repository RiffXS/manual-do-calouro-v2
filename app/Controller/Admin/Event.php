<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Evento as EntityCalendar;
use App\Utils\Tools\Alert;
use App\Utils\Pagination;
use App\Utils\View;

class Event extends Page {

    /**
     * Método responsável por obter a renderização dos items de usuários para página
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

        // RENDERIZA O ITEM
        while ($obCalendar = $results->fetch(\PDO::FETCH_ASSOC)) {  
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/events/item',[
                'click'  => "onclick=deleteItem({$obCalendar['id_evento']})",
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
     * Método responsável por renderizar a view de listagem de usuários
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

    /**
     * Método responsavel por renderizar o formulario de cadastro de evento
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function getNewEvent(Request $request): string {
        // CONTEUDO DO FORMULARIO
        $content = View::render('/admin/modules/events/form', [
            'tittle' => 'Cadastrar Evento',
            'status' => Alert::getStatus($request),
            'descricao' => '',
            'data' => '',
            'botao' => 'Cadastrar'
        ]);
        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar Evento > MDC', $content, 'events');
    }

    /**
     * Método responsavel por cadastrar um novo evento
     * @param \App\Http\Request $request
     * 
     * @return void
     */
    public static function setNewEvent(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        $evento = $postVars['descricao'];
        $campus = $postVars['campus'];
        $data   = $postVars['data'];

        // NOVA INSTANCIA DE EVENTO
        $obEvent = new EntityCalendar;

        $obEvent->setDsc_evento($evento);
        $obEvent->setDat_evento($data);
        $obEvent->setFk_campus($campus);

        $obEvent->insertEvent();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/events?status=event_registered');
    }

    /**
     * Método responsavel por retornar o formulario de edição de um evento
     * @param \App\Http\Request $request
     * @param integer $id
     * 
     * @return string
     */
    public static function getEditEvent(Request $request, int $id): string {
        // OBTEM O EVENTO PELO ID
        $obEvent = EntityCalendar::getEventById($id);

        // VALIDA INSTANCIA DO OBJETO
        if (!$obEvent instanceof EntityCalendar) {
            $request->getRouter()->redirect('admin/events');
        }
        // CONTEUDO DO FORMULARIO
        $content = View::render('/admin/modules/events/form', [
            'tittle'    => 'Editar Evento',
            'status'    => Alert::getStatus($request),
            'descricao' => $obEvent->getDsc_evento(),
            'data'      => $obEvent->getDat_evento(),
            'campus'    => $obEvent->getFk_campus(),
            'botao'     => 'Editar'
        ]);
        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Editar Evento > MDC', $content, 'events');
    }

    /**
     * Método responsavel por atualizar um evento
     * @param \App\Http\Request $request
     * @param integer $id
     * 
     * @return void
     */
    public static function setEditEvent(Request $request, int $id): void {
        // OBTENDO O EVENTO PELO ID
        $obEvent = EntityCalendar::getEventById($id);

        // VALIDA A INSTANCIA DO OBJETO
        if (!$obEvent instanceof EntityCalendar) {
            $request->getRouter()->redirect('/admin/events');
        }
        // POST VARS
        $postVars = $request->getPostVars();

        $obEvent->setId_evento($id);
        $obEvent->setDsc_evento($postVars['descricao']);
        $obEvent->setDat_evento($postVars['data']);
        $obEvent->setFk_campus($postVars['campus']);

        $obEvent->updateEvent();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/events?status=event_updated');
    }

    /**
     * Método responsável por excluir um evento
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setDeleteEvent(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // OBTENDO O USUÁRIO DO BANCO DE DADOS
        $obEvent = EntityCalendar::getEventById((int)$postVars['id']);

        // VALIDA A INSTANCIA
        if (!$obEvent instanceof EntityCalendar) {
            $request->getRouter()->redirect('/admin/events');
        }
        // EXCLUIR DEPOIMENTO
        $obEvent->deleteEvent();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/events?status=event_deleted');
    }
}