<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\Pagination;
use App\Utils\View;

class Page {

    /**
     * Módulos disponiveis no painel
     */
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link'  => URL.'/admin'
        ],
        'comments' => [
            'label' => 'Comentários',
            'link'  => URL.'/admin/comments'
        ],
        'users' => [
            'label' => 'Usuários',
            'link'  => URL.'/admin/users'
        ],
        'events' => [
            'label' => 'Eventos',
            'link'  => URL.'/admin/events'
        ],
        'schedules' => [
            'label' => 'Aulas',
            'link'  => URL.'/admin/schedules'
        ]
    ];

    /**
     * Método responsável por retornar o conteúdo (view) estrutura genérica do painel
     * @param  string $tittle
     * @param  string $content
     * 
     * @return string 
     */
    public static function getPage(string $tittle, string $content): string {
        return View::render('admin/page', [
            'title'   => $tittle,
            'content' => $content
        ]);
    }

    /**
     * Método responsável por renderizar a view do painel
     * @param  string $currentModule
     * 
     * @return string
     */
    private static function getMenu(string $currentModule): string {
        // LINKS DO MENU
        $links = '';

        // ITERA OS MODULOS
        foreach (self::$modules as $hash=>$module) {
            $links .= View::render('admin/menu/link', [
                'label'   => $module['label'],
                'link'    => $module['link'],
                'current' => $hash == $currentModule ? 'text-success' : ''
            ]);
        }
        // RETORNA A RENDERIZAÇÃO DO MENU
        return View::render('admin/menu/box', [
            'links' => $links
        ]); 
    }

    /**
     * Método responsável por renderizar a view do painel com conteudos dinâmicos
     * @param  string $title
     * @param  string $contenct
     * @param  string $currentModule
     * 
     * @return string
     */
    public static function getPanel(string $tittle, string $content, string $currentModule): string {
        // RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        // RETORNA A PÁGINA RENDERIZADA
        return self::getPage($tittle, $contentPanel);
    }

    /**
     * Método responsavel por renderizar os input hidden
     * @param array $array
     * 
     * @return string
     */
    protected static function setHiddens(array $array): string {
        // INICIALIZAÇÃO DE VARIAVEL
        $content = '';

        
        for ($i = 0; $i < count($array); $i++) {
            $keys = array_keys($array);

            $content .= View::render('/shared/hidden', [
                'id'    => $keys[$i],
                'value' => $array[$keys[$i]]
            ]);
        }
        // RETORNA O CONTEUDO
        return $content;
    }

    /**
     * Método responsável por retornar um link da paginação
     * @param array  $queryParams
     * @param array  $page
     * @param string $url
     * @param string $label
     * 
     * @return string
     */
    private static function getPaginationLink(array $queryParams, array $page, string $url, string $label = null): string {
        // ALTERA PAGINA    
        $queryParams['page'] = $page['page'];

        // LINK
        $link = $url.'?'.http_build_query($queryParams);

        // VIEW
        return View::render('shared/pagination/link',[
            'page'   => $label ?? $page['page'],
            'link'   => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }
    
    /**
     * Método responsável por renderizar o layout de paginação
     * @param \App\Http\Request $request
     * @param \App\Utils\Pagination $obPagination
     * 
     * @return string
     */
    protected static function getPagination(Request $request, Pagination $obPagination): string {
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

        // RENDERIZA OS LINKS
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
        return View::render('shared/pagination/box',[
            'links' => $links
        ]); 
    }
}