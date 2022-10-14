<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page {

    /**
     * Méthodo responsavel por rendenizar o topo da pagina
     * @return string
     */
    private static function getHeader() {
        return View::render('pages/header');
    }

    /**
     * Méthodo responsavel por rendenizar o rodape da pagina
     * @return string
     */
    private static function getFooter() {
        return View::render('pages/footer');
    }

    /**
     * Methodo responsavel por retornar um link da paginação
     * @param array $queryParams
     * @param array $page
     * @param string $url
     * @return
     */
    private static function getPaginationLink($queryParams, $page, $url, $label = null) {
        // ALTERA PAGINA    
        $queryParams['page'] = $page['page'];

        // LINK
        $link = $url.'?'.http_build_query($queryParams);

        // VIEW
        return View::render('pages/pagination/link',[
            'page' => $label ?? $page['page'],
            'link' => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }

    /**
     * Methodo responsavel por rendenizar o layout de paginação
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination) {
        // OBTER AS PAGINAS
        $pages = $obPagination->getPages();

        // VERIFICA A QUANTIDADE DE PAGINAS
        if (count($pages) <= 1) return '';

        // LINKS
        $links = '';

        // URL ATUAL sem GET
        $url = $request->getRouter()->getCurrentUrl();

        // GET
        $queryParams = $request->getQueryParams();

        // PAGINA ATUAL
        $currentPage = $queryParams['page'] ?? 1;

        // LIMITE DE PAGINAS
        $limit = getenv('PG_LIMIT');

        // MEIO DA PAGINAÇÃO
        $middle = ceil($limit / 2);

        // INICIO DA PAGINAÇÃO
        $start = $middle > $currentPage ? 0 : $currentPage - $middle;

        // AJUSTA O FINAL DA PAGINAÇÃO
        $limit += $start;

        // AJUSTA O INICIO DA PAGINAÇÃO
        if ($limit > count($pages)) {
            $diff = $limit - count($pages);
            $start -= $diff;
        }
        // LINK INICIAL
        if ($start > 0) {
            $links .= self::getPaginationLink($queryParams, reset($pages), $url, '<<'); 
        }

        // RENDENIZA OS LINKS
        foreach ($pages as $page) {
            // VERIFICA O STRAT DA PAGINAÇÃO
            if ($page['page'] <= $start) continue;

            if ($page['page'] > $limit) {
                $links .= self::getPaginationLink($queryParams, end($pages), $url, '>>'); 

                break;
            }
            $links .= self::getPaginationLink($queryParams, $page, $url);   
        }
        // RETORNA BOX DE PAGINAÇÃO
        return View::render('pages/pagination/box',[
            'links' => $links
        ]); 
    }
    
    /**
     * Metodo responsavel por retornar o contéudo (view) da pagina generica
     * 
     * @return string 
     */
    public static function getPage($title, $content) {
        
        return View::render('pages/page',[
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }
}