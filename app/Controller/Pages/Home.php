<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Models\Comentario as EntityComment;
use App\Utils\Sanitize;
use App\Utils\Session;
use App\Utils\View;
use App\Utils\Pagination;

class Home extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página home
     * @return string
     */
    public static function getHome($request) {
        
        $form = '<p>Faço login para comentar</p>';

        if (Session::isLogged()) {
            $form = View::render('pages/components/home/form');
        }

        // VIEW DA HOME
        $content = View::render('pages/home', [
            'formulario'  => $form,
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

    /**
     * 
     * 
     */
    public static function setNewComment(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();

        // VERIFICA HTML INJECT
        if (Sanitize::validateForm($postVars)) {
            $request->getRouter()->redirect('/signup?status=invalid_chars');
        }
        // SANITIZA O ARRAY
        $postVars = Sanitize::sanitizeForm($postVars); 

        $obComment = new EntityComment;

        $obComment->setFK_id_usuario(Session::getId());
        $obComment->setDsc_comentario($postVars['mensagem']);
        $obComment->setAdd_data();

        echo '<pre>'; print_r($postVars); echo '</pre>'; exit;
    }
}