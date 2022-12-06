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

        $sql = 'SELECT id_contato, nom_usuario, dsc_tipo, dsc_contato FROM tipo_contato tc JOIN contato c ON (tc.id_tipo = c.fk_tipo_contato_id_tipo) JOIN servidor s ON (c.fk_servidor_fk_usuario_id_usuario = s.fk_usuario_id_usuario) JOIN usuario u ON (s.fk_usuario_id_usuario = u.id_usuario)';

        // RESULTADOS DA PAGINA
        $results = EntityContact::getDscContacts('id_contato ASC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obContact = $results->fetch(\PDO::FETCH_ASSOC)) {
            $modal = View::render('admin/modules/contacts/delete',[
                'id' => $obContact['id_contato']
            ]);

            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/contacts/item',[
                'id'    => $obContact['id_contato'],
                'user'  => $obContact['nom_usuario'],
                'tipo'  => $obContact['dsc_tipo'],
                'dsc'   => $obContact['dsc_contato'],
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

    public static function getEditContact(Request $request, int $id) {

        $obContact = EntityContact::getContactById($id);

        echo '<pre>'; print_r($obContact); echo '</pre>'; exit;
    }
}