<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page {

    /**
     * Módulos disponiveis no painel
     */
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link'  => URL.'/admin'
        ],
        'testimonies' => [
            'label' => 'Depoimentos',
            'link'  => URL.'/admin/testimonies'
        ],
        'users' => [
            'label' => 'Usuários',
            'link'  => URL.'/admin/users'
        ]
    ];

    /**
     * Methodo responsavel por retornar o conteudo (view) estrutura generica do painel
     * @param  string $tittle
     * @param  string $content
     * @return string 
     */
    public static function getPage($tittle, $content) {
        return View::render('admin/page', [
            'title'   => $tittle,
            'content' => $content
        ]);
    }

    /**
     * Methodo responsavel por rendenizar a view do painel
     * @param  string $currentModule
     * @return string
     */
    private static function getMenu($currentModule) {
        // LINKS DO MENU
        $links = '';

        // ITERA OS MODULOS
        foreach (self::$modules as $hash=>$module) {
            $links .= View::render('admin/menu/link', [
                'label'   => $module['label'],
                'link'    => $module['link'],
                'current' => $hash == $currentModule ? 'text-danger' : ''
            ]);
        }

        // RETORNA A RENDENIZAÇÃO DO MENU
        return View::render('admin/menu/box', [
            'links' => $links
        ]); 
    }

    /**
     * Methodo responsavel por rendenizar a view do painel com conteudos dinamicos
     * @param  string $title
     * @param  string $contenct
     * @param  string $currentModule
     * @return string
     */
    public static function getPanel($tittle, $content, $currentModule) {
        // RENDENIZA A VIEW DO PAINEL
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        // RETORNA A PAGINA RENDENIZADA
        return self::getPage($tittle, $contentPanel);
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

        // RENDENIZA OS LINKS
        foreach ($pages as $page) {
            // ALTERA PAGINA    
            $queryParams['page'] = $page['page'];

            // LINK
            $link = $url.'?'.http_build_query($queryParams);

            // VIEW
            $links .= View::render('admin/pagination/link',[
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);   
        }
        // RETORNA BOX DE PAGINAÇÃO
        return View::render('admin/pagination/box',[
            'links' => $links
        ]); 
    }
}