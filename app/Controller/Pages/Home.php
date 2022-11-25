<?php

namespace App\Controller\Pages;

use App\Models\Comentario as EntityComment;
use App\Utils\View;
use App\Utils\Pagination;

class Home extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página home
     * @return string
     */
    public static function getHome($request) {
        // VIEW DA HOME
        $content = View::render('pages/home', [
            'comentarios' => self::getCommentsItems($request, $obPagination),
            'pagination'  => parent::getPagination($request, $obPagination)
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Home', $content, 'home');
    }

    /**
     * Método responsável por obter a renderização dos items de depoimentos para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    private static function getCommentsItems($request, &$obPagination) {
        // DEPOIMENTOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityComment::getComments(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

        // RESULTADOS DA PAGINA
        $results = EntityComment::getComments(null, 'id_comentario DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obComment = $results->fetchObject(EntityComment::class)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('pages/components/comment/item',[
                'nome' => $obComment->nome,
                'mensagem' => $obComment->mensagem,
                'data' => date('d/m/Y H:i:s',strtotime($obComment->data))
            ]);
        }
        // RETORNA OS DEPOIMENTOS
        return $itens;
    }
}