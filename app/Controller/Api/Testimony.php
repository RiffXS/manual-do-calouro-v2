<?php

namespace App\Controller\Api;

use App\Models\Testimony as EntityTestimony;
use App\Utils\Pagination;

use Exception;

class Testimony extends Api {
    
    /**
     * Methodo responsavel por obter a rendenização dos items de depoimentos para página
     * @param \App\Http\Request $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getTetimoniesItems($request, &$obPagination) {
        // DEPOIMENTOS
        $itens = [];

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
            $itens[] = [
                'id'       => (int)$obTestimony->id,
                'nome'     => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data'     => $obTestimony->data
            ];
        }

        // RETORNA OS DEPOIMENTOS
        return $itens;
    }

    /**
     * Methodo responsavel retornar os detalhes da API
     * @param \App\Http\Request
     * @return array
     */
    public static function getTestimonies($request) {
        return [
            'depoimentos' => self::getTetimoniesItems($request, $obPagination),
            'paginacao'   => parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Methodo responsavel por retornar os detalhes de um depoimento
     * @param \App\Http\Request
     * @param integer $id
     * @return array
     */
    public static function getTestimony($request, $id) {
        // VALIDA O ID DO DEPOIMENTO
        if (!is_numeric($id)) {
            throw new Exception("O id '".$id."'não e valido", 400);
        }
        // BUSCA DEPOIMENTO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA SE O DEPOIMENTO EXISTE
        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("O depoimento ".$id." não foi encontrado", 404);
        }
        // RETORNA OS DETALHES DO DEPOIMENTO
        return [
            'id'       => (int)$obTestimony->id,
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data'     => $obTestimony->data
        ];
    }

    /**
     * Methodo responsavel por cadastrar um novo depoimento
     * @param \App\Http\Request $request
     */
    public static function setNewTestimony($request) {
        // POST VARS
        $postVars = $request->getPostVars();
    
        // VALIDA OS CAMPOS OBRIGATORIOS
        if (!isset($postVars['nome']) or !isset($postVars['mensagem'])) {
            throw new Exception("Os campos 'nome' e 'mensagem' são obrigatorios", 400);
        }
        // NOVO DEPOIMENTO
        $obTestimony = new EntityTestimony;

        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];

        $obTestimony->cadastrarTestimony();

        // RETORNA OS DETALHES DO DEPOIMENTO CADASTRADO
        return [
            'id'       => (int)$obTestimony->id,
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data'     => $obTestimony->data
        ];
    }

    /**
     * Methodo responsavel por atualizar um depoimento
     * @param \App\Http\Request $request
     */
    public static function setEditTestimony($request, $id) {
        // POST VARS
        $postVars = $request->getPostVars();
    
        // VALIDA OS CAMPOS OBRIGATORIOS
        if (!isset($postVars['nome']) or !isset($postVars['mensagem'])) {
            throw new Exception("Os campos 'nome' e 'mensagem' são obrigatorios", 400);
        }

        // BUSCA O DEPOIMENTO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA A INSTANCIA
        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("O depoimento ".$id." não foi encontrado", 404);
        }
        // ATUALIZA O DEPOIMENTO
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];

        $obTestimony->atualizarTestimony();

        // RETORNA OS DETALHES DO DEPOIMENTO ATUALIZADO
        return [
            'id'       => (int)$obTestimony->id,
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data'     => $obTestimony->data
        ];
    }

    /**
     * Methodo responsavel por excluir um depoimento
     * @param \App\Http\Request $request
     */
    public static function setDeleteTestimony($request, $id) {
        // BUSCA O DEPOIMENTO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // VALIDA A INSTANCIA
        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("O depoimento ".$id." não foi encontrado", 404);
        }
        // EXCLUI O DEPOIMENTO
        $obTestimony->excluirTestimony();

        // RETORNA O SUCESSO DA EXCLUSÃO
        return [
            'sucesso'  => true 
        ];
    }
}