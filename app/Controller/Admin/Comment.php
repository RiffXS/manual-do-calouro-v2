<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Comentario as EntityComment;
use App\Utils\View;
use App\Utils\Pagination;
use App\Utils\Tools\Alert;

class Comment extends Page {

    /**
     * Método responsável por obter a renderização dos items de depoimentos para página
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    private static function getCommentsItems(Request $request, &$obPagination): string {
        // DEPOIMENTOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityComment::getComments(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        // RESULTADOS DA PAGINA
        $results = EntityComment::getDscComments('id_comentario DESC', $obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obComment = $results->fetch(\PDO::FETCH_ASSOC)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/comments/item',[
                'edit'  => "onclick=viewComment({$obComment['id_comentario']})",
                'click' => "onclick=deleteItem({$obComment['id_comentario']})",
                'id'       => $obComment['id_comentario'],
                'user'     => $obComment['nom_usuario'],
                'mensagem' => $obComment['dsc_comentario'],
                'data'     => $obComment['dt_comentario']
            ]);
        }
        // RETORNA OS DEPOIMENTOS
        return $itens;
    }

    /**
     * Método responsável por renderizar a view de listagem de depoimentos
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getComments(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/comments/index', [
            'itens'      => self::getCommentsItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Commentários > MDC', $content, 'comments');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return string
     */
    public static function getDeleteComment(Request $request, int $id): string {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obComment = EntityComment::getCommentById($id);

        // VALIDA A INSTANCIA
        if (!$obComment instanceof EntityComment) {
            $request->getRouter()->redirect('/admin/comments');
        }
        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/comments/delete', [
            'usuario'  => $obComment->getFK_id_usuario(),
            'mensagem' => $obComment->getDsc_comentario(),
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Excluir Comentário > MDC', $content, 'comments');
    }

    /**
     * Método responsável por excluir um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return void
     */
    public static function setDeleteComment(Request $request): void {
        $postVars = $request->getPostVars();

        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obComment = EntityComment::getCommentById($postVars['id']);

        // VALIDA A INSTANCIA
        if (!$obComment instanceof EntityComment) {
            $request->getRouter()->redirect('/admin/comments');
        }
        // EXCLUIR DEPOIMENTO
        $obComment->deleteComment();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/comments?status=comment_deleted');
    }
}