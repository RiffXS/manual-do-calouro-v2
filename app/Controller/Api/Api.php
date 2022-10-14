<?php

namespace App\Controller\Api;

class Api {
    
    /**
     * Methodo responsavel retornar os detalhes da API
     * @param \App\Http\Request
     * @return array
     */
    public static function getDetails($request) {
        return [
            'nome'   => 'API - WDEV',
            'versao' => 'v1.0.0',
            'autor'  => 'Henrique Dalmagro',
        ];
    }

    /**
     * Methodo responsavel por retornar os detalhes da paginação
     * @param \App\Http\Request
     * @param \App\Utils\Pagination 
     * @return array
     */
    protected static function getPagination($request, $obPagination) {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // PAGINA
        $pages = $obPagination->getPages();

        // RETORNO 
        return [
            'pagina_atual'       => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidade_paginas' => !empty($pages) ? count($pages) : 1,
        ];
    }
}