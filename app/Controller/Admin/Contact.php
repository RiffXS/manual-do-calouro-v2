<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Contato as EntityContact;
use App\Utils\Tools\Alert;
use App\Utils\Pagination;
use App\Utils\View;

class Contact extends Page {

    /**
     * Método responsavel por obter a rendenização dos items de usuarios para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    private static function getContactItems(Request $request, &$obPagination): string {
        // USUARIOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityContact::getContacts(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        // RESULTADOS DA PAGINA
        $results = EntityContact::getContacts(null, 'id_contato DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obContact = $results->fetchObject(EntityContact::class)) {
            $modal = View::render('admin/modules/contacts/delete',[
                'id' => $obContact->getId_contato()
            ]);

            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/contacts/item',[
                'id'    => $obContact->getId_contato(),
                'user'  => $obContact->getFk_usuario(),
                'tipo'  => $obContact->getFk_tipo(),
                'dsc'   => $obContact->getDsc_contato(),
                'modal' => $modal
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
    public static function getContacts(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/contacts/index', [
            'itens'      => self::getContactItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Contatos > MDC', $content, 'contacts');
    }
}