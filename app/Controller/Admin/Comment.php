<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Comentario as EntityComment;
use App\Utils\View;
use App\Utils\Pagination;
use App\Utils\Tools\Alert;

class Comment extends Page {

    /**
     * Methodo responsavel por obter a rendenização dos items de depoimentos para página
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
        $results = EntityComment::getComments(null, 'id DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obComment = $results->fetchObject(EntityComment::class)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/comment/item',[
                'id'   => $obComment->id,
                'nome' => $obComment->nome,
                'mensagem' => $obComment->mensagem,
                'data' => date('d/m/Y H:i:s',strtotime($obComment->data))
            ]);
        }

        // RETORNA OS DEPOIMENTOS
        return $itens;
    }


    /**
     * Methodo responsavel por rendenizar a view de listagem de depoimentos
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getComments(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/comment/index', [
            'itens'      => self::getCommentsItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Depoimentos  > WDEV', $content, 'testimonies');
    }

    /**
     * Methodo responsavel por retornar o formulario de cadastro de um novo depoimento
     * @param \App\Http\Request
     * 
     * @return string
     */
    public static function getNewComment(Request $request): string {
        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/comments/form', [
            'tittle'   => 'Cadastrar depoimento',
            'nome'     => '',
            'mensagem' => '',
            'status'   => ''
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar depoimento  > WDEV', $content, 'testimonies');
    }

    /**
     * Methodo responsavel por cadastrar um depoimento no banco
     * @param \App\Http\Request
     * 
     * @return void
     */
    public static function setNewComment(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();
        
        // NOVA INSTANCIA DE DEPOIMENTO
        $obComment = new EntityComment;
        $obComment->nome = $postVars['nome'] ?? '';
        $obComment->mensagem = $postVars['mensagem'] ?? '';
        $obComment->insertComment();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obComment->id.'/edit?status=created');
    }

    /**
     * Methodo responsavel por retornar o formulario edição de um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return string
     */
    public static function getEditComment(Request $request, int $id): string {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obComment = EntityComment::getCommentById($id);

        // VALIDA A INSTANCIA
        if (!$obComment instanceof EntityComment) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/testimonies/form', [
            'tittle'   => 'Editar depoimento', 
            'nome'     => $obComment->nome,
            'mensagem' => $obComment->mensagem,
            'status'   => Alert::getStatus($request)
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Editar depoimento  > WDEV', $content, 'testimonies');
    }

    /**
     * Methodo responsavel por gravar a atualização de um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return void
     */
    public static function setEditComment(Request $request, int $id): void {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obComment = EntityComment::getCommentById($id);

        // VALIDA A INSTANCIA
        if (!$obComment instanceof EntityComment) {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        // POST VARS
        $postVars = $request->getPostVars();

        // ATUALIZA A INSTANCIA
        $obComment->nome = $postVars['nome'] ?? $obComment->nome;
        $obComment->mensagem = $postVars['mensagem'] ?? $obComment->mensagem;
        $obComment->updateComment();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obComment->id.'/edit?status=updated');
    }

    /**
     * Methodo responsavel por retornar o formulario exclusão de um depoimento
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
            $request->getRouter()->redirect('/admin/testimonies');
        }

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/testimonies/delete', [
            'nome'     => $obComment->nome,
            'mensagem' => $obComment->mensagem,
        ]);

        // RETORNA A PAGINA COMPLETA
        return parent::getPanel('Excluir depoimento  > WDEV', $content, 'testimonies');
    }

    /**
     * Methodo responsavel por excluir um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return void
     */
    public static function setDeleteComment(Request $request, int $id): void {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obComment = EntityComment::getCommentById($id);

        // VALIDA A INSTANCIA
        if (!$obComment instanceof EntityComment) {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        // EXCLUIR DEPOIMENTO
        $obComment->deleteComment();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }
}