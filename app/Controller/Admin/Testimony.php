<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Models\Testimony as EntityTestimony;
use App\Utils\View;
use App\Utils\Pagination;
use App\Utils\Tools\Alert;

class Testimony extends Page {

    /**
     * Methodo responsavel por obter a rendenização dos items de depoimentos para página
     * @param \App\Http\Request $request
     * @param Pagination $obPagination
     * 
     * @return string
     */
    private static function getTetimoniesItems(Request $request, Pagination &$obPagination): string {
        // DEPOIMENTOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        // RESULTADOS DA PAGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obTestimony = $results->fetchObject(EntityTestimony::class)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('admin/modules/testimonies/item',[
                'id'   => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y H:i:s',strtotime($obTestimony->data))
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
    public static function getTestimonies(Request $request): string {
        // CONTEUDO DA HOME
        $content = View::render('admin/modules/testimonies/index', [
            'itens'      => self::getTetimoniesItems($request, $obPagination),
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
    public static function getNewTestimony(Request $request): string {
        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/testimonies/form', [
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
    public static function setNewTestimony(Request $request): void {
        // POST VARS
        $postVars = $request->getPostVars();
        
        // NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'] ?? '';
        $obTestimony->mensagem = $postVars['mensagem'] ?? '';
        $obTestimony->cadastrarTestimony();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
    }

    /**
     * Methodo responsavel por retornar o formulario edição de um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return string
     */
    public static function getEditTestimony(Request $request, int $id): string {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA A INSTANCIA
        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/testimonies/form', [
            'tittle'   => 'Editar depoimento', 
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
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
    public static function setEditTestimony(Request $request, int $id): void {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA A INSTANCIA
        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        // POST VARS
        $postVars = $request->getPostVars();

        // ATUALIZA A INSTANCIA
        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->atualizarTestimony();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
    }

    /**
     * Methodo responsavel por retornar o formulario exclusão de um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * 
     * @return string
     */
    public static function getDeleteTestimony(Request $request, int $id): string {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA A INSTANCIA
        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        // CONTEUDO DO FORMULARIO
        $content = View::render('admin/modules/testimonies/delete', [
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
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
    public static function setDeleteTestimony(Request $request, int $id): void {
        // OBTENDO O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA A INSTANCIA
        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        // EXCLUIR DEPOIMENTO
        $obTestimony->excluirTestimony();

        // REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }
}