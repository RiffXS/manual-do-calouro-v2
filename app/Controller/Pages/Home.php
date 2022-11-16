<?php

namespace App\Controller\Pages;

use App\Models\Testimony as EntityTestimony;
use App\Utils\View;
use App\Utils\Pagination;

class Home extends Page {

    /**
     * Método responsável por retornar o contéudo (view) da página home
     * @return string
     */
    public static function getHome() {
        // VIEW DA HOME
        $content = View::render('pages/home');

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('Home', $content, 'home');
    }

    /**
     * Método responsável por obter a renderização dos items de depoimentos para página
     * @param \App\Http\Request $request
     * @param Pagination $obPagination
     * 
     * @return string
     */
    private static function getTetimoniesItems($request, &$obPagination) {
        // DEPOIMENTOS
        $itens = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        // PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

        // RESULTADOS DA PAGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        // RENDENIZA O ITEM
        while ($obTestimony = $results->fetchObject(EntityTestimony::class)) {
            // VIEW De DEPOIMENTOSS
            $itens .= View::render('pages/testimony/item',[
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y H:i:s',strtotime($obTestimony->data))
            ]);
        }

        // RETORNA OS DEPOIMENTOS
        return $itens;
    }

    /**
     * Método responsável por retornar o contéudo (view) de depoimenots
     * @param \App\Http\Request $request
     * 
     * @return string 
     */
    public static function getTestimonies($request){

        // VIEW De DEPOIMENTOSS
        $content =  View::render('pages/testimonies',[
           'itens' => self::getTetimoniesItems($request, $obPagination),
           'pagination' => parent::getPagination($request, $obPagination)
        ]);

        // RETORNA A VIEW DA PAGINA
        return parent::getPage('DEPOIMENTOS > WDEV', $content);
    }

    /**
     * Método responsável por cadastrar um depoimento
     * @param \App\Http\Request $request
     * 
     * @return string
     */
    public static function inserTestimony($request) {
        // DADOS DO POST
        $postVars = $request->getPostVars();

        // NOVA INSTANCIA
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];

        $obTestimony->cadastrarTestimony();

        // RETORNA A PAGINA DE LISTAGEM DE DEPOIMENTOS
        return self::getTestimonies($request);
    }
}